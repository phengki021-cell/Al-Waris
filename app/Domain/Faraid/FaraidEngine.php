<?php

namespace App\Domain\Faraid;

/**
 * Mesin faraid Tahap 2 — Al-Waris.
 *
 * SCOPE TERVERIFIKASI (aktif):
 *  - Suami / istri (termasuk beberapa istri), anak laki-laki & perempuan,
 *    ayah (tanpa anak laki-laki), ibu, nenek dari ibu (kasus gharawain),
 *    saudara sedarah laki-laki/perempuan.
 *  - Ahli waris pengganti (perwakilan): kakek dari ayah (menggantikan ayah
 *    yang tiada) serta cucu laki-laki/cucu perempuan dari anak laki-laki
 *    (menggantikan tempat ayahnya yang lebih dulu wafat).
 *  - Metode: amal ummi, 'aul, radd, gharawain (berlabel beda pendapat).
 *
 * TIDAK DIDUKUNG (not_supported + alasan):
 *  - Ayah + anak laki-laki: butuh validasi ahli faraid sebelum aktif.
 *  - Saudara seayah/seibu, dzawil arham.
 *  - Kakek + saudara sedarah dan kakek + cucu (umariyyatân): beda pendapat
 *    klasik, ditolak aman agar tidak menghasilkan angka menyesatkan.
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
 *  - Kakek dari ayah: terhalang oleh ayah yang masih ada maupun oleh
 *    anak laki-laki; selain itu meniru mekanik ayah (fard 1/6 bila ada
 *    anak perempuan, lalu menyerap sisa).
 *  - Cucu laki-laki (dari anak laki-laki): terhalang oleh anak laki-laki;
 *    selain itu asabah pengganti ayahnya (2 satuan) dan pada hubungan laki-laki menurun (far') mendahului ayah/kakek pada sisa.
 *  - Cucu perempuan (dari anak laki-laki): terhalang oleh anak perempuan;
 *    sendirian 1/2, dua lebih 2/3; bersama cucu laki-laki asabah 1 banding 2.
 *
 * Kelas ini murni domain: TIDAK mengimpor Illuminate.
 */
final class FaraidEngine
{
    /** Versi mesin — ditampilkan di halaman hasil untuk ketertelusuran. */
    public const VERSION = '0.4.0';

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
        'grandfather' => 'Kakek dari ayah',
        'grandson' => 'Cucu laki-laki',
        'granddaughter' => 'Cucu perempuan',
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

    /** @return array{0: bool, 1: int, 2: int, 3: int, 4: bool, 5: bool, 6: bool, 7: int, 8: int, 9: bool, 10: int, 11: int} */
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
            && empty($input['grandfather']) && $int('grandsons') === 0 && $int('granddaughters') === 0
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
            $bool('grandfather'),
            $int('grandsons'),
            $int('granddaughters'),
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
        bool $grandfather,
        int $grandsons,
        int $granddaughters,
    ): FaraidResult {
        $trace = [];
        $notes = ['Seluruh hasil berlabel DEMO — wajib verifikasi ahli faraid sebelum dipakai untuk keputusan nyata.'];
        $trace[] = 'Langkah 1 — identifikasi ahli waris selesai.';

        // Keturunan mencakup cucu dari anak laki-laki (pengganti anak yang
        // telah wafat) — memengaruhi porsi pasangan dan ibu.
        $grandchildren = $grandsons > 0 || $granddaughters > 0;
        $hasChildren = $sons > 0 || $daughters > 0 || $grandchildren;
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

        if ($mother && $maternalGrandmother && $siblingCount > 0) {
            // Catatan: kasus ibu + nenek-ibu bersama KETURUNAN sudah ditangkap
            // oleh pemeriksaan di atas ($maternalGrandmother && $hasChildren),
            // sehingga di sini cukup memeriksa saudara.
            $trace[] = 'Gharawain hanya didukung pada komposisi sederhana.';

            return FaraidResult::notSupported(
                'Kasus gharawain (ibu + nenek-ibu) baru didukung pada komposisi sederhana.',
                $trace
            );
        }

        if ($grandfather && $grandchildren) {
            $trace[] = 'Kakek bersama cucu: memuat beda pendapat klasik (umariyyatân).';

            return FaraidResult::notSupported(
                'Komposisi kakek dari ayah + cucu dari anak laki-laki memuat beda pendapat klasik (umariyyatân) — menunggu validasi ahli faraid.',
                $trace
            );
        }

        if ($grandfather && $siblingCount > 0) {
            $trace[] = 'Kakek bersama saudara sedarah: memuat beda pendapat klasik (umariyyatân).';

            return FaraidResult::notSupported(
                'Komposisi kakek dari ayah + saudara sedarah memuat beda pendapat klasik (umariyyatân) — menunggu validasi ahli faraid.',
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

        // Kakek meniru mekanik ayah ketika ayah tiada (lazim dipakai kitab JIL:
        // "kakek menggantikan ayah"). Fard 1/6 hanya bila ada anak perempuan.
        if ($grandfather && $daughters > 0) {
            $fard['grandfather'] = [1, 6, 1, false];
            $trace[] = 'Langkah 2 — kakek dari ayah: fard 1/6 (ada anak perempuan) + menyerap sisa (analog ayah).';
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

        // Cucu laki-laki menutup jalan fard saudari (introspeksi: jalur
        // laki-laki yang lebih dekat menghalangi), konsisten dengan anak laki.
        if (! $father && $sons === 0 && $grandsons === 0 && $daughters === 0 && $sisters > 0 && $brothers === 0) {
            $fard['sister'] = $sisters === 1
                ? [1, 2, 1, false]
                : [2, 3, $sisters, false];
            $trace[] = 'Langkah 2 — saudara perempuan '.($sisters === 1 ? 'sendiri 1/2' : "($sisters) bersama 2/3").'.';
        }

        // Cucu perempuan dari anak laki-laki (bint al-ibn): bagian fard seperti
        // anak perempuan, hanya bila jalur laki-laki (anak maupun cucu laki)
        // tidak hadir dan tidak ada anak perempuan langsung.
        if ($sons === 0 && $grandsons === 0 && $daughters === 0 && $granddaughters > 0) {
            $fard['granddaughter'] = $granddaughters === 1
                ? [1, 2, 1, false]
                : [2, 3, $granddaughters, false];
            $trace[] = 'Langkah 2 — cucu perempuan '.($granddaughters === 1 ? 'sendiri 1/2' : "($granddaughters) bersama 2/3").' (perwakilan ayahnya).';
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

        // Cucu dari anak laki-laki: asabah menggantikan tempat ayahnya yang
        // lebih dulu wafat. Jalur laki-laki menurun (far') mendahului ayah
        // dan kakek untuk sisa harta.
        if ($sons === 0 && $grandsons > 0) {
            $absorbers[] = ['code' => 'grandson', 'units_each' => 2, 'count' => $grandsons];

            if ($daughters === 0 && $granddaughters > 0) {
                $absorbers[] = ['code' => 'granddaughter', 'units_each' => 1, 'count' => $granddaughters];
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
        if (! $father && $sons === 0 && $grandsons === 0 && $daughters > 0 && $brothers === 0 && $sisters > 0) {
            $absorbers[] = ['code' => 'sister', 'units_each' => 1, 'count' => $sisters];
        }

        // Ayah menyerap sisa hanya bila jalur far' (laki-laki menurun) kosong:
        // anak laki-laki maupun cucu laki-laki mendahului ayah.
        $fatherAbsorbs = $father && $sons === 0 && $grandsons === 0;
        $grandfatherAbsorbs = $grandfather && ! $father && $sons === 0 && $grandsons === 0;
        $hasAbsorber = $absorbers !== [] || $fatherAbsorbs || $grandfatherAbsorbs;

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

                    if ($grandfatherAbsorbs) {
                        $grandfatherShare = isset($fard['grandfather'])
                            ? RationalNumber::of($fard['grandfather'][0], $fard['grandfather'][1])->add($remainder)
                            : $remainder;

                        $shares['grandfather'] = ['fraction' => $grandfatherShare->simplify(), 'count' => 1, 'disputed' => false];
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
        if ($grandfather && ! $father) {
            $notes[] = 'Kakek dari ayah diperlakukan setara dengan ayah — pendapat yang lazim dipakai kitab-kitab JIL Indonesia ("kakek menggantikan ayah"). Komposisi kakek bersama saudara/cucu ditolak aman karena memuat beda pendapat (umariyyatân).';
        }
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

        $blocked = $this->blockedHeirs(
            $father,
            $maternalGrandmother,
            $grandfather,
            $sons,
            $grandsons,
            $granddaughters,
            $daughters,
            $brothers,
            $sisters,
        );

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
        bool $grandfather,
        int $sons,
        int $grandsons,
        int $granddaughters,
        int $daughters,
        int $brothers,
        int $sisters,
    ): array {
        $blocked = [];

        $add = function (string $code, string $reason) use (&$blocked): void {
            foreach ($blocked as $entry) {
                if ($entry['code'] === $code) {
                    return; // hindari kartu duplikat untuk satu ahli waris
                }
            }

            $blocked[] = ['code' => $code, 'name' => self::NAMES[$code], 'reason' => $reason];
        };

        // Ayah menghalangi seluruh saudara (hijab hirman), nenek-ibu, dan kakek.
        if ($father) {
            if ($brothers > 0) {
                $add('brother', 'Terhalang oleh ayah almarhum/almarhumah.');
            }

            if ($sisters > 0) {
                $add('sister', 'Terhalang oleh ayah almarhum/almarhumah.');
            }

            if ($maternalGrandmother) {
                $add('maternal_grandmother', 'Terhalang oleh ayah almarhum/almarhumah.');
            }

            if ($grandfather) {
                $add('grandfather', 'Terhalang oleh ayah almarhum/almarhumah — kakek dari ayah tidak mewarisi selama ayah masih ada.');
            }
        }

        // Anak laki-laki menutup jalur kakek maupun cucu (anak dari anak).
        if ($sons > 0) {
            if ($grandfather) {
                $add('grandfather', 'Terhalang oleh anak laki-laki almarhum/almarhumah.');
            }

            if ($grandsons > 0) {
                $add('grandson', 'Terhalang oleh anak laki-laki almarhum/almarhumah.');
            }

            if ($granddaughters > 0) {
                $add('granddaughter', 'Terhalang oleh anak laki-laki almarhum/almarhumah.');
            }
        }

        // Cucu laki-laki menghalangi saudara perempuan kecuali saat
        // saudara laki-laki hadir (yang menyetirnya menjadi asabah) —
        // konsisten dengan peran anak laki-laki pada kasus serupa.
        if ($grandsons > 0 && $brothers === 0 && $sisters > 0) {
            $add('sister', 'Terhalang oleh cucu laki-laki almarhum/almarhumah (perwakilan jalur laki-laki).');
        }

        // Anak perempuan menghalangi cucu perempuan dari anak laki-laki:
        // perwakilan hanya berlaku bila jalur langsung tidak hadir.
        if ($daughters > 0 && $granddaughters > 0) {
            $add('granddaughter', 'Terhalang oleh anak perempuan almarhum/almarhumah (perwakilan tidak berlaku bila ada anak perempuan langsung).');
        }

        // Anak laki-laki menghalangi saudara perempuan kecuali saat
        // saudara laki-laki hadir (yang menyetirnya menjadi asabah).
        if ($sons > 0 && $brothers === 0 && $sisters > 0) {
            $add('sister', 'Terhalang oleh anak laki-laki almarhum/almarhumah.');
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
