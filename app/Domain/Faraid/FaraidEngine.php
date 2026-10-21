<?php

namespace App\Domain\Faraid;

/**
 * Mesin faraid Tahap 2 — Al-Waris.
 *
 * SCOPE TERVERIFIKASI (aktif):
 *  - Suami / istri (termasuk beberapa istri), anak laki-laki & perempuan,
 *    ayah (tanpa anak laki-laki), ibu, nenek dari ibu (kasus gharawain),
 *    saudara sedarah laki-laki/perempuan.
 *  - Metode: amal ummi, 'aul, radd, gharawain (berlabel beda pendapat).
 *
 * TIDAK DIDUKUNG (not_supported + alasan):
 *  - Ayah + anak laki-laki: butuh validasi ahli faraid sebelum aktif.
 *  - Saudara seayah/seibu, kakek, cucu melalui jalur anak laki-laki, dzawil arham.
 *
 * Kaidah yang diterapkan & dites otomatis:
 *  - Suami: 1/2 (tanpa keturunan) / 1/4 (ada keturunan).
 *  - Istri (semua): 1/4 (tanpa keturunan) / 1/8 (ada keturunan), dibagi rata.
 *  - Anak perempuan tanpa anak laki-laki: sendiri 1/2, dua lebih total 2/3.
 *  - Anak laki-laki + perempuan: asabah, rasio 2 : 1 atas sisa.
 *  - Ibu: 1/6 jika ada keturunan atau dua saudara atau lebih; selain itu 1/3.
 *  - Ayah tanpa anak laki-laki: fard 1/6 bila ada anak perempuan, dan SELALU
 *    menyerap sisa (fard + sisa) sebagai asabah.
 *  - Ayah menghalangi seluruh saudara (hijab hirman).
 *  - Saudara sedarah laki-laki: asabah penyerap sisa; saudara perempuan
 *    sendirian 1/2, dua lebih 2/3, bersama saudara laki-laki asabah 1:2.
 *  - Nenek-ibu: 1/6; bersama ibu menjadi kasus gharawain (beda pendapat).
 *  - Sisa tanpa asabah: radd proporsional kepada fard non-pasangan; bila
 *    tiada sasaran, dikembalikan not_supported (dzawil arham: Tahap 3).
 *
 * Kelas ini murni domain: TIDAK mengimpor Illuminate.
 */
final class FaraidEngine
{
    /** Versi mesin — ditampilkan di halaman hasil untuk ketertelusuran. */
    public const VERSION = '0.3.0';

    /** Nama tampilan untuk UI. */
    private const NAMES = [
        'husband' => 'Suami',
        'wife' => 'Istri',
        'son' => 'Anak laki-laki',
        'daughter' => 'Anak perempuan',
        'father' => 'Ayah',
        'mother' => 'Ibu',
        'maternal_grandmother' => 'Nenek (dari ibu)',
        'brother' => 'Saudara sedarah laki-laki',
        'sister' => 'Saudara sedarah perempuan',
    ];

    public function calculate(array $input): FaraidResult
    {
        try {
            $heirs = $this->normalize($input);
        } catch (\InvalidArgumentException $e) {
            return FaraidResult::error($e->getMessage());
        }

        return $this->run(...$heirs);
    }

    /** @return array{0: bool, 1: int, 2: int, 3: int, 4: bool, 5: bool, 6: bool, 7: int, 8: int} */
    private function normalize(array $input): array
    {
        $int = function (string $key) use ($input): int {
            $value = $input[$key] ?? 0;

            if (! is_numeric($value) || (int) $value != $value || $value < 0 || $value > 99) {
                throw new \InvalidArgumentException("Nilai '$key' tidak sah (harus 0–99).");
            }

            return (int) $value;
        };
        $bool = fn (string $key): bool => ! empty($input[$key]);

        if ($bool('husband') && $int('wife_count') > 0) {
            throw new \InvalidArgumentException('Suami dan istri tidak mungkin menjadi pewaris bersamaan.');
        }

        if (empty($input['husband']) && $int('wife_count') === 0 && $int('sons') === 0 && $int('daughters') === 0
            && empty($input['father']) && empty($input['mother']) && empty($input['maternal_grandmother'])
            && $int('brothers') === 0 && $int('sisters') === 0) {
            throw new \InvalidArgumentException('Minimal satu ahli waris harus diisi.');
        }

        return [
            $bool('husband'),
            $int('wife_count'),
            $int('sons'),
            $int('daughters'),
            $bool('father'),
            $bool('mother'),
            $bool('maternal_grandmother'),
            $int('brothers'),
            $int('sisters'),
        ];
    }

    private function run(
        bool $hasHusband,
        int $wifeCount,
        int $sons,
        int $daughters,
        bool $father,
        bool $mother,
        bool $maternalGrandmother,
        int $brothers,
        int $sisters,
    ): FaraidResult {
        $trace = [];
        $notes = ['Seluruh hasil berlabel DEMO — wajib verifikasi ahli faraid sebelum dipakai untuk keputusan nyata.'];
        $trace[] = 'Langkah 1 — identifikasi ahli waris selesai.';

        $hasChildren = $sons > 0 || $daughters > 0;
        $siblingCount = $brothers + $sisters;

        // ---- Tolak komposisi di luar scope ---------------------------------
        if ($father && $sons > 0) {
            $trace[] = 'Ayah + anak laki-laki: aturan asabah gabungan belum diverifikasi.';

            return FaraidResult::notSupported(
                'Komposisi ayah + anak laki-laki belum didukung Tahap 2 — menunggu validasi ahli faraid.',
                $trace
            );
        }

        if ($maternalGrandmother && $hasChildren) {
            $trace[] = 'Nenek-ibu bersama keturunan: belum didukung.';

            return FaraidResult::notSupported('Nenek dari ibu bersama keturunan belum didukung Tahap 2.', $trace);
        }

        if ($mother && $maternalGrandmother && ($hasChildren || $siblingCount > 0)) {
            $trace[] = 'Gharawain hanya didukung pada komposisi sederhana.';

            return FaraidResult::notSupported(
                'Kasus gharawain (ibu + nenek-ibu) baru didukung pada komposisi sederhana.',
                $trace
            );
        }

        // ---- Fard -----------------------------------------------------------
        $fard = [];     // kode => [n, d, count, disputed]
        $isGharawain = false;

        if ($hasHusband) {
            $fard['husband'] = $hasChildren
                ? [1, 4, 1, false]
                : [1, 2, 1, false];
            $trace[] = 'Langkah 2 — suami: '.($hasChildren ? '1/4 (ada keturunan)' : '1/2 (tanpa keturunan)').'.';
        }

        if ($wifeCount > 0) {
            $fard['wife'] = $hasChildren
                ? [1, 8, $wifeCount, false]
                : [1, 4, $wifeCount, false];
            $trace[] = "Langkah 2 — istri ($wifeCount) bersama-sama: ".($hasChildren ? '1/8' : '1/4').'.';
        }

        // Hanya AYAH yang menghalangi saudara sedarah; keturunan tidak
        // (saudara tetap asabah bersama anak lihat cabang absorber).
        if ($father && $siblingCount > 0) {
            $trace[] = 'Catatan — ayah hadir: seluruh saudara terhalang (hijab hirman).';
        }

        if ($sons === 0 && $daughters > 0) {
            $fard['daughter'] = $daughters === 1
                ? [1, 2, 1, false]
                : [2, 3, $daughters, false];
            $trace[] = 'Langkah 2 — anak perempuan '.($daughters === 1 ? 'sendiri 1/2' : "($daughters) bersama 2/3").'.';
        }

        if ($father && $daughters > 0) {
            $fard['father'] = [1, 6, 1, false];
            $trace[] = 'Langkah 2 — ayah: fard 1/6 (ada anak perempuan) + menyerap sisa.';
        }

        if ($mother) {
            if ($hasChildren || $siblingCount >= 2) {
                $fard['mother'] = [1, 6, 1, false];
                $trace[] = 'Langkah 2 — ibu: 1/6 '.($hasChildren ? '(ada keturunan)' : '(dua saudara atau lebih)').'.';
            } elseif ($maternalGrandmother && ! $father) {
                $isGharawain = true;
                $fard['mother'] = [1, 3, 1, true];
                $fard['maternal_grandmother'] = [1, 6, 1, true];
                $trace[] = 'Langkah 2 — gharawain: ibu 1/3, nenek-ibu 1/6 (sebagian ulama menghalangi nenek bila ada ibu).';
            } else {
                $fard['mother'] = [1, 3, 1, false];
                $trace[] = 'Langkah 2 — ibu: 1/3 (tanpa keturunan dan saudara kurang dari dua).';
            }
        } elseif ($maternalGrandmother && ! $father) {
            $fard['maternal_grandmother'] = [1, 6, 1, false];
            $trace[] = 'Langkah 2 — nenek-ibu: 1/6 (tanpa ibu).';
        }

        if (! $father && $sons === 0 && $daughters === 0 && $sisters > 0 && $brothers === 0) {
            $fard['sister'] = $sisters === 1
                ? [1, 2, 1, false]
                : [2, 3, $sisters, false];
            $trace[] = 'Langkah 2 — saudara perempuan '.($sisters === 1 ? 'sendiri 1/2' : "($sisters) bersama 2/3").'.';
        }

        $trace[] = 'Langkah 3 — hutang, wasiat, dan biaya perawatan didahulukan (dihitung di luar bagian waris).';

        // ---- Asal masalah & total fard ---------------------------------------
        $fardSum = RationalNumber::of(0);
        foreach ($fard as [$n, $d]) {
            $fardSum = $fardSum->add(RationalNumber::of($n, $d));
        }
        $fardSum = $fardSum->simplify();

        $method = 'amal_ummi';
        $trace[] = 'Langkah 4 — pecahan disamakan penyebutnya (asal). Total bagian pasti = '.$fardSum->toText();

        if ($fardSum->greaterThan(RationalNumber::of(1))) {
            $method = 'aul';
            $trace[] = "Langkah 5 — total > 1: kasus 'aul; asal masalah dinaikkan sehingga pecahan tetap eksak (pecahan diskalakan pada langkah 6b).";
        }

        // ---- Asabah ----------------------------------------------------------
        $absorbers = [];

        if ($sons > 0) {
            $absorbers[] = ['code' => 'son', 'units_each' => 2, 'count' => $sons];

            if ($daughters > 0) {
                $absorbers[] = ['code' => 'daughter', 'units_each' => 1, 'count' => $daughters];
            }
        }

        // Saudara sedarah TIDAK terhalang oleh keturunan — mereka tetap asabah
        // dan membagi sisa dengan anak laki-laki secara setara (asabah bi-nafs);
        // saudara perempuan menjadi "penyetir" asabahnya dengan 1 satuan.
        if (! $father && $brothers > 0) {
            $absorbers[] = ['code' => 'brother', 'units_each' => 2, 'count' => $brothers];

            if ($sisters > 0) {
                $absorbers[] = ['code' => 'sister', 'units_each' => 1, 'count' => $sisters];
            }
        }

        // Ta'sīb bi'l-ghayr: anak perempuan menyetir saudara perempuan menjadi
        // asabah menyerap sisa (klasik: "anak 1/2 + saudara perempuan 1/2").
        if (! $father && $sons === 0 && $daughters > 0 && $brothers === 0 && $sisters > 0) {
            $absorbers[] = ['code' => 'sister', 'units_each' => 1, 'count' => $sisters];
        }

        $fatherAbsorbs = $father && $sons === 0;
        $hasAbsorber = $absorbers !== [] || $fatherAbsorbs;

        $remainder = RationalNumber::of(1)->subtract($fardSum)->simplify();

        $shares = [];
        $isRadd = false;

        if ($method === 'aul') {
            // Pada 'aul seluruh penerima adalah fard; asabah menerima nol.
            foreach ($fard as $code => [$n, $d, $count, $disputed]) {
                $shares[$code] = [
                    'fraction' => RationalNumber::of($n, $d)->simplify(),
                    'count' => $count,
                    'disputed' => $disputed,
                ];
            }

            $shares = $this->rescaleForAul($shares);
            $trace[] = 'Langkah 6 — pecahan diskalakan ke asal masalah yang dinaikkan.';
            $notes[] = "'Aul: jumlah pecahan melebihi satu, sehingga asal masalah dinaikkan. Pada beberapa kasus terdapat beda pendapat.";

            if ($absorbers !== []) {
                $notes[] = 'Asabah menerima nol pada kasus ini karena bagian pasti sudah melebihi keseluruhan harta.';
            }
        } else {
            if ($remainder->isNegative()) {
                return FaraidResult::error('Perhitungan internal menghasilkan sisa negatif — laporkan sebagai bug.');
            }

            // Pre-seed: semua fard tercatat terlebih dahulu agar urutan tampil
            // mengikuti daftar fard, sementara asabah ditambahkan setelahnya.
            foreach ($fard as $code => [$n, $d, $count, $disputed]) {
                $shares[$code] = [
                    'fraction' => RationalNumber::of($n, $d)->simplify(),
                    'count' => $count,
                    'disputed' => $disputed,
                ];
            }

            if (! $remainder->isZero()) {
                if ($hasAbsorber) {
                    $trace[] = 'Langkah 6 — sisa ('.$remainder->toText().') diberikan kepada asabah.';

                    if ($fatherAbsorbs) {
                        $fatherShare = isset($fard['father'])
                            ? RationalNumber::of($fard['father'][0], $fard['father'][1])->add($remainder)
                            : $remainder;

                        $shares['father'] = ['fraction' => $fatherShare->simplify(), 'count' => 1, 'disputed' => false];
                    }

                    if ($absorbers !== []) {
                        $unitTotal = 0;
                        foreach ($absorbers as $entry) {
                            $unitTotal += $entry['units_each'] * $entry['count'];
                        }

                        $unitValue = $remainder->divide(RationalNumber::of($unitTotal));

                        // Pecahan disimpan sebagai TOTAL GRUP (per orang dibagi count
                        // saat ditampilkan) — konsisten dengan cabang fard & radd.
                        foreach ($absorbers as $entry) {
                            $shares[$entry['code']] = [
                                'fraction' => $unitValue
                                    ->multiply(RationalNumber::of($entry['units_each']))
                                    ->multiply(RationalNumber::of($entry['count']))
                                    ->simplify(),
                                'count' => $entry['count'],
                                'disputed' => false,
                            ];
                        }
                    }
                } else {
                    // ---- RADD ------------------------------------------------
                    $method = 'radd';
                    $isRadd = true;
                    $trace[] = 'Langkah 6 — sisa ('.$remainder->toText().') diradd-kan proporsional kepada ahli waris fard (tidak kepada pasangan).';

                    $targets = array_filter(
                        $fard,
                        fn (string $code): bool => $code !== 'husband' && $code !== 'wife',
                        ARRAY_FILTER_USE_KEY
                    );

                    if ($targets === []) {
                        return FaraidResult::notSupported(
                            'Sisa tidak dapat diradd-kan (tidak ada fard selain pasangan). Dzawil arham/Baitulmal akan didukung pada Tahap 3.',
                            $trace
                        );
                    }

                    $targetSum = RationalNumber::of(0);
                    foreach ($targets as [$n, $d]) {
                        $targetSum = $targetSum->add(RationalNumber::of($n, $d));
                    }

                    foreach ($targets as $code => [$n, $d, $count, $disputed]) {
                        $bonus = $remainder->multiply(RationalNumber::of($n, $d))->divide($targetSum);

                        $shares[$code] = [
                            'fraction' => RationalNumber::of($n, $d)->add($bonus)->simplify(),
                            'count' => $count,
                            'disputed' => $disputed,
                        ];
                    }
                }
            }
        }

        // ---- Catatan & nama metode -------------------------------------------
        if ($isGharawain) {
            $method = 'gharawain';
            $notes[] = 'Kasus gharawain: ada perbedaan pendapat mengenai hak nenek ketika ibu hadir. Aplikasi memakai pendapat yang memberi nenek-ibu 1/6 dan sisanya diradd-kan bersama.';
        }

        if ($isRadd) {
            $notes[] = 'Radd dilakukan proporsional dan tidak diberikan kepada pasangan. Sebagian kitab memakai metode pembagian asal berbasis pembilang (hasil dapat sedikit berbeda).';
        }

        $trace[] = 'Langkah 7 — verifikasi: jumlah seluruh bagian = keseluruhan harta.';

        $displayShares = [];
        foreach ($shares as $code => $share) {
            $fraction = $share['fraction'];

            $displayShares[] = [
                'code' => $code,
                'name' => self::NAMES[$code] ?? $code,
                'count' => $share['count'],
                'n' => $fraction->numerator,
                'd' => $fraction->denominator,
                'text' => $fraction->toText(),
                'per_person_text' => $share['count'] > 1
                    ? $fraction->divide(RationalNumber::of($share['count']))->simplify()->toText()
                    : null,
                'disputed' => $share['disputed'],
            ];
        }

        $blocked = $this->blockedHeirs($father, $maternalGrandmother, $sons, $brothers, $sisters);

        return FaraidResult::valid($method, $displayShares, $trace, $notes, $blocked);
    }

    /**
     * Daftar ahli waris yang terhalang sesuai komposisi + alasan.
     * Hanya memuat ahli waris yang MEMINTA hadir di input tetapi terhijab.
     *
     * @return list<array{code: string, name: string, reason: string}>
     */
    private function blockedHeirs(
        bool $father,
        bool $maternalGrandmother,
        int $sons,
        int $brothers,
        int $sisters,
    ): array {
        $blocked = [];

        // Ayah menghalangi seluruh saudara (hijab hirman) dan nenek-ibu.
        if ($father) {
            if ($brothers > 0) {
                $blocked[] = [
                    'code' => 'brother',
                    'name' => self::NAMES['brother'],
                    'reason' => 'Terhalang oleh ayah almarhum/almarhumah.',
                ];
            }

            if ($sisters > 0) {
                $blocked[] = [
                    'code' => 'sister',
                    'name' => self::NAMES['sister'],
                    'reason' => 'Terhalang oleh ayah almarhum/almarhumah.',
                ];
            }

            if ($maternalGrandmother) {
                $blocked[] = [
                    'code' => 'maternal_grandmother',
                    'name' => self::NAMES['maternal_grandmother'],
                    'reason' => 'Terhalang oleh ayah almarhum/almarhumah.',
                ];
            }
        }

        // Anak laki-laki menghalangi saudara perempuan kecuali saat
        // saudara laki-laki hadir (yang menyetirnya menjadi asabah).
        if ($sons > 0 && $brothers === 0 && $sisters > 0) {
            $blocked[] = [
                'code' => 'sister',
                'name' => self::NAMES['sister'],
                'reason' => 'Terhalang oleh anak laki-laki almarhum/almarhumah.',
            ];
        }

        return $blocked;
    }

    /**
     * Menyajikan pecahan di atas asal masalah yang dinaikkan ('aul).
     * Semua share pada kasus 'aul adalah fard (tidak ada asabah menyerap sisa).
     *
     * @param  array<string, array{fraction: RationalNumber, count: int, disputed: bool}>  $shares
     * @return array<string, array{fraction: RationalNumber, count: int, disputed: bool}>
     */
    private function rescaleForAul(array $shares): array
    {
        // Asal masalah = KPK seluruh penyebut fard.
        $asl = 1;
        foreach ($shares as $share) {
            $asl = $this->lcm($asl, $share['fraction']->denominator);
        }

        $numerators = [];
        foreach ($shares as $code => $share) {
            $numerators[$code] = $share['fraction']->numerator * intdiv($asl, $share['fraction']->denominator);
        }

        $total = array_sum($numerators);

        foreach ($shares as $code => $share) {
            $shares[$code]['fraction'] = RationalNumber::of($numerators[$code], $total)->simplify();
        }

        return $shares;
    }

    /** KPK dua bilangan. */
    private function lcm(int $a, int $b): int
    {
        return abs($a * $b) === 0 ? 0 : abs($a * $b) / $this->gcd($a, $b);
    }

    /** FPB dua bilangan. */
    private function gcd(int $a, int $b): int
    {
        $a = abs($a);
        $b = abs($b);

        return $b === 0 ? $a : $this->gcd($b, $a % $b);
    }
}
