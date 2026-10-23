<?php

namespace App\Services;

use App\Domain\Faraid\EstateDistribution;
use App\Domain\Faraid\FaraidEngine;

/**
 * Papan keterangan dinamis ahli waris — Al-Waris (Tahap 'Ashabah & Hijab).
 *
 * Lapisan TERJEMAH output mesin faraid menjadi:
 *  - status/bagian/alasan/nominal per ahli waris,
 *  - jenis 'ashabah (bin nafsi / bil ghair / ma'al ghair),
 *  - hijab (hirman: terhalang seluruhnya; nuqshan: bagiannya berkurang).
 *
 * Lapisan ini TIDAK menghitung hukum faraid sendiri: seluruh pecahan,
 * sisa, 'aul, radd, dan daftar terhalang diambil dari output FaraidEngine
 * (aturan persisnya di-copy dari logika mesin: keturunan = anak/cucu
 * dari jalur laki-laki; ibu 1/6 bila ada keturunan atau dua saudara
 * atau lebih; dsb.).
 */
final class HeirExplanationService
{
    /** Urutan tampil papan keterangan. */
    private const ORDER = [
        'husband', 'wife', 'father', 'mother', 'grandfather', 'maternal_grandmother',
        'sons', 'daughters', 'grandsons', 'granddaughters', 'brothers', 'sisters',
    ];

    /** Peta kode input (jamak) → kode share mesin (bentuk tunggal). */
    private const SHARE_CODE = [
        'sons' => 'son',
        'daughters' => 'daughter',
        'grandsons' => 'grandson',
        'granddaughters' => 'granddaughter',
        'brothers' => 'brother',
        'sisters' => 'sister',
    ];

    /** Bahasa sederhana untuk ahli waris yang belum dipilih. */
    private const IDLE_HINT = [
        'husband' => 'Pilih SUAMI jika suami masih hidup saat pewaris wafat.',
        'wife' => 'Pilih ISTRI jika istri masih hidup saat pewaris wafat.',
        'father' => 'Pilih AYAH jika ayah masih hidup saat pewaris wafat.',
        'mother' => 'Pilih IBU jika ibu masih hidup saat pewaris wafat.',
        'grandfather' => 'Pilih KAKEK jika pewaris tidak meninggalkan ayah.',
        'maternal_grandmother' => 'Pilih NENEK jika nenek masih hidup dan berhak menurut aturan faraid.',
        'sons' => 'Isi jumlah anak laki-laki bila ada.',
        'daughters' => 'Isi jumlah anak perempuan bila ada.',
        'grandsons' => 'Isi jumlah cucu laki-laki (dari anak laki-laki) bila ada.',
        'granddaughters' => 'Isi jumlah cucu perempuan (dari anak laki-laki) bila ada.',
        'brothers' => 'Isi jumlah saudara sedarah laki-laki bila ada.',
        'sisters' => 'Isi jumlah saudara sedarah perempuan bila ada.',
    ];

    /** Nama akrab penyebab hijab untuk ditampilkan di panel Hijab. */
    private const CAUSE_NAMES = [
        'Ayah' => 'Ayah',
        'Anak laki-laki' => 'Anak laki-laki',
        'Anak perempuan' => 'Anak perempuan',
        'Cucu laki-laki' => 'Cucu laki-laki',
    ];

    /**
     * Bangun papan keterangan dari input formulir.
     *
     * @param  array<string, mixed>  $heirs  format sama dengan buildDistribution() kalkulator
     * @param  array{estate?: float|int|string, debts?: float|int|string, bequest?: float|int|string, funeral?: float|int|string}  $money
     * @return array{status: string, note: ?string, has_money: bool, entries: list<array<string, mixed>>}
     */
    public function explain(array $heirs, array $money): array
    {
        // Saat harta belum diisi, pecahan tetap ditayangkan (harta simbolik 1)
        // untuk mesin; nominal disembunyikan karena has_money = false.
        $hasMoney = (float) ($money['estate'] ?? 0) > 0.0;
        $engineMoney = $hasMoney
            ? $money
            : ['estate' => 1, 'debts' => 0, 'bequest' => 0, 'funeral' => 0];

        /** @var EstateDistribution $dist */
        $dist = app(EstateCalculationService::class)->calculate($heirs, $engineMoney);
        $display = $dist->toDisplayArray();

        $shareRows = [];
        foreach ($display['shares'] as $row) {
            $shareRows[$row['code']] = $row;
        }

        $blockedRows = [];
        foreach ($display['blocked'] as $row) {
            $blockedRows[$row['code']] = $row;
        }

        $entries = [];
        foreach (self::ORDER as $code) {
            $shareCode = self::SHARE_CODE[$code] ?? $code;
            $entries[] = $this->buildEntry($code, $shareCode, $heirs, $shareRows[$shareCode] ?? null, $blockedRows[$shareCode] ?? null, $hasMoney);
        }

        return [
            'status' => $display['status'],
            'note' => $display['status'] === 'valid' ? null : (($display['notes'][0] ?? null) ?: (($display['trace'][0] ?? null))),
            'has_money' => $hasMoney,
            'entries' => $entries,
        ];
    }

    /** Keturunan langsung atau wakilnya — memengaruhi bagian pasangan & ibu. */
    private function hasDescendant(array $heirs): bool
    {
        return (int) $heirs['sons'] > 0
            || (int) $heirs['daughters'] > 0
            || (int) $heirs['grandsons'] > 0
            || (int) $heirs['granddaughters'] > 0;
    }

    /**
     * @param  array<string, mixed>  $heirs
     * @return array<string, mixed>
     */
    private function buildEntry(string $code, string $shareCode, array $heirs, ?array $share, ?array $blocked, bool $hasMoney): array
    {
        [$selected, $count] = $this->selectedAndCount($code, $heirs);

        if ($blocked !== null && $selected) {
            $cause = $this->hijabCause($shareCode, $heirs);

            return [
                'code' => $code,
                'name' => $blocked['name'],
                'count' => $count,
                'status' => 'mahjub',
                'label' => 'Terhalang (mahjub)',
                'share_text' => null,
                'per_person_share' => null,
                'amount_text' => null,
                'per_person_amount_text' => null,
                'reason' => $this->kaidahHirman($shareCode, $heirs),
                'asabah_type' => null,
                'asabah_type_label' => null,
                'asabah_way' => null,
                'hijab' => [
                    'type' => 'hirman',
                    'type_label' => 'Hijab Hirman',
                    'cause' => $cause,
                    'cause_label' => 'Ahli waris penyebab: '.$cause,
                    'reason' => $blocked['reason'],
                    'final_share' => null,
                    'final_amount' => null,
                ],
            ];
        }

        if ($share !== null && $selected) {
            $asabahType = $this->asabahType($shareCode, $heirs);
            $rp = isset($share['total_rp']) && $hasMoney ? 'Rp '.number_format((float) $share['total_rp'], 2, ',', '.') : null;
            $rpPerson = $share['per_person_cents'] !== null && $hasMoney ? 'Rp '.number_format((float) $share['per_person_rp'], 2, ',', '.') : null;

            $hijab = $this->nuqshanHeir($shareCode, $heirs);

            return [
                'code' => $code,
                'name' => $share['name'],
                'count' => $share['count'],
                'status' => $asabahType !== null ? 'asabah' : 'fard',
                'label' => $asabahType !== null ? "Asabah (sisa harta)" : 'Berhak menerima',
                'share_text' => $share['text'],
                'per_person_share' => $share['per_person_text'],
                'amount_text' => $rp,
                'per_person_amount_text' => $rpPerson,
                'reason' => $asabahType !== null
                    ? $this->asabahReason($shareCode, $heirs, $asabahType)
                    : $this->reason($shareCode, $heirs, $share),
                'asabah_type' => $asabahType,
                'asabah_type_label' => $asabahType !== null ? $this->asabahTypeLabel($asabahType) : null,
                'asabah_way' => $asabahType !== null ? $this->asabahWay($shareCode) : null,
                'hijab' => $hijab,
            ];
        }

        if ($selected && $blocked === null && $share === null) {
            // Komposisi belum didukung mesin — jangan pernah tampilkan angka.
            return [
                'code' => $code,
                'name' => FaraidEngine::NAMES[self::SHARE_CODE[$code] ?? $code] ?? $code,
                'count' => $count,
                'status' => 'pending',
                'label' => 'Kasus belum didukung',
                'share_text' => null,
                'per_person_share' => null,
                'amount_text' => null,
                'per_person_amount_text' => null,
                'reason' => 'Kombinasi ahli waris ini belum didukung mesin agar tidak menghasilkan angka yang menyesatkan.',
                'asabah_type' => null,
                'asabah_type_label' => null,
                'asabah_way' => null,
                'hijab' => null,
            ];
        }

        return [
            'code' => $code,
            'name' => FaraidEngine::NAMES[self::SHARE_CODE[$code] ?? $code] ?? $code,
            'count' => $count,
            'status' => 'idle',
            'label' => 'Tidak dipilih',
            'share_text' => null,
            'per_person_share' => null,
            'amount_text' => null,
            'per_person_amount_text' => null,
            'reason' => self::IDLE_HINT[$code] ?? 'Pilihan belum diisi.',
            'asabah_type' => null,
            'asabah_type_label' => null,
            'asabah_way' => null,
            'hijab' => null,
        ];
    }

    /** @return array{0: bool, 1: int} */
    private function selectedAndCount(string $code, array $heirs): array
    {
        return match ($code) {
            'husband' => [(bool) ($heirs['husband'] ?? false), 1],
            'wife' => [(int) $heirs['wife_count'] > 0, (int) $heirs['wife_count']],
            'father', 'mother', 'maternal_grandmother', 'grandfather' => [(bool) $heirs[$code], 1],
            default => [((int) $heirs[$code]) > 0, (int) $heirs[$code]],
        };
    }

    /**
     * Jenis 'ashabah dari kode share (output mesin), mengikuti kaidah distribusi:
     *  - bin nafsi   : penyerap sisa karena dirinya sendiri (laki-laki menurun/ber ersamaan,
     *                  ayah & kakek menyerap sisa bila tidak ada anak laki-laki lagi).
     *  - bil ghair   : perempuan yang menjadi 'ashabah bersama saudara laki-laki sederajat.
     *  - ma'al ghair : saudara perempuan menyerap sisa bersama anak/cucu perempuan.
     *
     * @param  array<string, mixed>  $heirs
     */
    private function asabahType(string $code, array $heirs): ?string
    {
        return match ($code) {
            'son', 'grandson' => 'bin_nafsi',
            'brother' => 'bin_nafsi',
            'father', 'grandfather' => (int) $heirs['sons'] === 0 ? 'bin_nafsi' : null,
            'daughter' => (int) $heirs['sons'] > 0 ? 'bil_ghair' : null,
            'granddaughter' => (int) $heirs['grandsons'] > 0 ? 'bil_ghair' : null,
            'sister' => (int) $heirs['brothers'] > 0
                ? 'bil_ghair'
                : (((int) $heirs['daughters'] > 0 || (int) $heirs['granddaughters'] > 0) ? 'ma_al_ghair' : null),
            default => null,
        };
    }

    private function asabahTypeLabel(string $type): string
    {
        return match ($type) {
            'bin_nafsi' => "'Ashabah bin Nafsi",
            'bil_ghair' => "'Ashabah bil Ghair",
            'ma_al_ghair' => "'Ashabah ma'al Ghair",
            default => "'Ashabah",
        };
    }

    /** Alasan menjadi 'ashabah menurut jenisnya — persis format mesin. */
    private function asabahReason(string $code, array $heirs, string $type): string
    {
        $others = match ($type) {
            'bil_ghair' => 'bersama laki-laki sederajat: pembagian 2 : 1 (dua bagian laki-laki, satu bagian perempuan)',
            'ma_al_ghair' => 'bersama anak perempuan/cucu perempuan (menolong menyerap sisa)',
            default => 'karena dirinya sendiri dan mendapat sisa setelah bagian pasti dibagikan',
        };

        $ratioNote = $this->ratioNote($heirs);

        return match ($code) {
            'son' => "Menjadi 'ashabah karena dirinya sendiri.$ratioNote",
            'grandson' => "Mewakili tempat ayahnya yang lebih dulu wafat; menjadi 'ashabah karena dirinya sendiri.",
            'brother' => "Menjadi 'ashabah karena dirinya sendiri (urutan saudara laki-laki sekandung).",
            'father', 'grandfather' => "Menyerap sisa harta sebagai asabah karena dirinya sendiri (tidak ada anak laki-laki yang menutup jalur turut menurun).",
            'daughter' => "Membagi sisa harta {$others}.",
            'granddaughter' => "Membagi sisa harta {$others}.",
            'sister' => "Membagi sisa harta {$others}.",
            default => "Menerima sisa harta sebagai {$this->asabahTypeLabel($type)}.",
        };
    }

    /** Catatan rasio bila bersama anak perempuan (2 bagian laki : 1 perempuan). */
    private function ratioNote(array $heirs): string
    {
        return (int) $heirs['daughters'] > 0
            ? ' — bersama anak perempuan: pembagian 2 bagian (laki-laki) : 1 bagian (perempuan)'
            : '';
    }

    /** Cara memperoleh sisa harta (teks pendek). */
    private function asabahWay(string $code): string
    {
        return match ($code) {
            'son', 'grandson', 'brother', 'father', 'grandfather' => 'Sisa harta dibagi kepadanya setelah seluruh bagian yang pasti (ashabul furudh) dikeluarkan.',
            default => 'Sisa harta dibagi bersama kelompoknya: laki-laki dua bagian, perempuan satu bagian.',
        };
    }

    /**
     * Kaidah hijab hirman untuk terhalang — menjelaskan singkat, sesuai
     * struktur penghalang yang dipakai mesin.
     */
    private function kaidahHirman(string $code, array $heirs): string
    {
        return match ($code) {
            'brother', 'sister' => (bool) $heirs['father']
                ? 'Kaidah hirman: ayah lebih dekat daripada saudara — seluruh saudara sekandung terhalang selama ayah masih ada.'
                : 'Kaidah hirman: jalur turut menurun (anak/cucu laki-laki) kedudukannya lewat saudara perempuan sehingga terhalang.',
            'grandson' => 'Kaidah hirman: anak lebih dekat daripada cucu — cucu dari anak laki-laki terhalang selama ada anak laki-laki.',
            'granddaughter' => ((int) $heirs['grandsons'] > 0)
                ? 'Kaidah hirman: anak laki-laki lebih dekat; perwakilan cucu tidak berlaku selama jalur langsung hadir.'
                : 'Kaidah hirman: perwakilan hanya berlaku bila jalur langsung tidak hadir — kehadiran anak perempuan menutup jalur cucu.',
            'maternal_grandmother' => 'Kaidah hirman: kehadiran ayah menghalangkan nenek-ibu.',
            'grandfather' => (bool) $heirs['father']
                ? 'Kaidah hirman: ayah yang masih ada menghalangkan kakek dari ayah.'
                : 'Kaidah hirman: anak laki-laki menutup jalur kakek dari ayah.',
            default => 'Kaidah hirman: yang lebih dekat kekerabatannya menghalangkan yang lebih jauh dulu.',
        };
    }

    /**
     * Nama penyebab hijab — diambil dari komposisi input (meniru fakta mesin):
     *  - saudara (brother/sister) → Ayah, atau Anak/Cucu laki-laki (jalur turun).
     *  - grandson/granddaughter   → Anak laki-laki, atau Anak perempuan (granddaughter).
     *  - maternal_grandmother     → Ayah.
     *  - grandfather              → Ayah, atau Anak laki-laki.
     */
    private function hijabCause(string $code, array $heirs): string
    {
        return match ($code) {
            'brother' => 'Ayah',
            'sister' => (bool) $heirs['father']
                ? 'Ayah'
                : ((int) $heirs['sons'] > 0 ? 'Anak laki-laki' : 'Cucu laki-laki'),
            'grandson' => 'Anak laki-laki',
            'granddaughter' => (int) $heirs['sons'] > 0 ? 'Anak laki-laki' : 'Anak perempuan',
            'maternal_grandmother' => 'Ayah',
            'grandfather' => (bool) $heirs['father'] ? 'Ayah' : 'Anak laki-laki',
            default => 'Yang lebih dekat kekerabatannya',
        };
    }

    /**
     * Hijab nuqshan (bagian berkurang) — hanya pasangan & ibu, kondisi persis
     * seperti mesin: keturunan (anak/cucu) untuk pasangan; ibu 1/6 bila ada
     * keturunan ATAU dua saudara atau lebih.
     * @param  array<string, mixed>  $heirs
     * @return array<string, mixed>|null
     */
    private function nuqshanHeir(string $code, array $heirs): ?array
    {
        $hasDescendant = $this->hasDescendant($heirs);

        [$base, $final, $cause] = match ($code) {
            'husband' => $hasDescendant ? ['1/2', '1/4', 'Keturunan (anak atau cucu yang mewarisi)'] : [null, null, null],
            'wife' => $hasDescendant ? ['1/4', '1/8', 'Keturunan (anak atau cucu yang mewarisi)'] : [null, null, null],
            'mother' => ($hasDescendant || ((int) $heirs['brothers'] + (int) $heirs['sisters']) >= 2)
                ? ['1/3', '1/6', $hasDescendant ? 'Keturunan (anak atau cucu yang mewarisi)' : 'Dua saudara atau lebih']
                : [null, null, null],
            default => [null, null, null],
        };

        if ($base === null) {
            return null;
        }

        return [
            'type' => 'nuqshan',
            'type_label' => 'Hijab Nuqshan',
            'cause' => $cause,
            'cause_label' => 'Penyebab berkurang: '.$cause,
            'reason' => 'Bagian '.($code === 'mother' ? 'ibu' : 'pasangan').' berkurang dari '.$base.' menjadi '.$final.' karena ada '.$cause.'.',
            'final_share' => $final,
            'final_amount' => null, // nominal diambil dari amount_text bila tersedia
        ];
    }

    /** @param  array<string, mixed>  $heirs */
    private function reason(string $code, array $heirs, array $share): string
    {
        $text = $share['text'];

        return match ($code) {
            'husband' => (str_starts_with($text, '1/2'))
                ? 'Tidak ada keturunan yang mewarisi, jadi bagian suami 1/2.'
                : 'Ada keturunan yang memengaruhi bagian, jadi bagian suami berkurang menjadi '.$text.'.',
            'wife' => (str_starts_with($text, '1/4'))
                ? 'Tidak ada keturunan yang mewarisi; bagian kolektif seluruh istri 1/4.'
                : 'Ada keturunan yang mewarisi; bagian kolektif seluruh istri '.$text.'.',
            'father' => 'Mendapat bagian menurut ketentuan faraid.',
            'mother' => (str_starts_with($text, '1/3'))
                ? 'Mendapat 1/3 karena tidak ada keturunan dan tidak ada dua saudara atau lebih.'
                : 'Mendapat 1/6 karena ada keturunan (atau menurut aturan kasus khusus faraid dua saudara ke atas).',
            'grandfather' => 'Pengganti ayah: bagian total '.$text.' — sisa diwariskan sebagai asabah bila tidak ada ayah.',
            'maternal_grandmother' => 'Mendapat 1/6.'.($share['disputed'] ? ' Bersama ibu: kasus gharawain (beda pendapat).' : ''),
            'son' => 'Menerima sisa harta (asabah).'.$this->ratioNote($heirs),
            'daughter' => 'Tidak ada anak laki-laki, jadi kelompok anak perempuan menerima '.$text.' (sisa diradd-kan bila ada fard lain).',
            'grandson' => 'Asabah pengganti ayahnya yang lebih dulu wafat.',
            'granddaughter' => 'Tidak ada cucu laki-laki, jadi kelompok cucu perempuan menerima '.$text.' (sisa diradd-kan bila ada fard lain).',
            'brother' => 'Asabah — menerima sisa harta setelah bagian fard dibagikan.',
            'sister' => 'Tidak ada saudara laki-laki; kelompok saudara perempuan menerima '.$text.'.',
            default => 'Mendapat bagian menurut ketentuan faraid.',
        };
    }
}
