<?php

namespace App\Services;

use App\Domain\Faraid\EstateDistribution;
use App\Domain\Faraid\FaraidEngine;

/**
 * Papan keterangan dinamis ahli waris — Al-Waris.
 *
 * Lapisan TERJEMAH hasil mesin faraid menjadi status/bagian/alasan/nominal
 * per ahli waris untuk ditampilkan secara langsung (live) saat pengguna
 * memilih atau mengubah ahli waris. TIDAK menghitung hukum faraid sendiri:
 * seluruh pecahan & sisa diambil dari output FaraidEngine.
 *
 * Status tiap entri:
 *  - fard     : berhak menerima bagian tertentu (ashabul furudh).
 *  - asabah   : berhak menerima sisa harta dalam keadaan yang berlaku.
 *  - mahjub   : dipilih pengguna tetapi terhalang (hijab) — alasan dari mesin.
 *  - idle     : belum dipilih.
 *  - pending  : dipilih, tetapi komposisi belum didukung mesin.
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

    /**
     * @param  array<string, mixed>  $heirs
     * @return array<string, mixed>
     */
    private function buildEntry(string $code, string $shareCode, array $heirs, ?array $share, ?array $blocked, bool $hasMoney): array
    {
        [$selected, $count] = $this->selectedAndCount($code, $heirs);

        if ($blocked !== null && $selected) {
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
                'reason' => $blocked['reason'],
            ];
        }

        if ($share !== null && $selected) {
            $asabah = $this->isAsabah($shareCode, $heirs);
            $rp = isset($share['total_rp']) && $hasMoney ? 'Rp '.number_format((float) $share['total_rp'], 2, ',', '.') : null;
            $rpPerson = $share['per_person_cents'] !== null && $hasMoney ? 'Rp '.number_format((float) $share['per_person_rp'], 2, ',', '.') : null;

            return [
                'code' => $code,
                'name' => $share['name'],
                'count' => $share['count'],
                'status' => $asabah ? 'asabah' : 'fard',
                'label' => $asabah ? 'Asabah (sisa harta)' : 'Berhak menerima',
                'share_text' => $share['text'],
                'per_person_share' => $share['per_person_text'],
                'amount_text' => $rp,
                'per_person_amount_text' => $rpPerson,
                'reason' => $this->reason($shareCode, $heirs, $share, $asabah),
            ];
        }

        if ($selected && $blocked === null && $share === null) {
            // Komposisi belum didukung mesin — jangan pernah tampilkan angka.
            return [
                'code' => $code,
                'name' => FaraidEngine::NAMES[$code] ?? $code,
                'count' => $count,
                'status' => 'pending',
                'label' => 'Kasus belum didukung',
                'share_text' => null,
                'per_person_share' => null,
                'amount_text' => null,
                'per_person_amount_text' => null,
                'reason' => 'Kombinasi ahli waris ini belum didukung mesin agar tidak menghasilkan angka yang menyesatkan.',
            ];
        }

        return [
            'code' => $code,
            'name' => FaraidEngine::NAMES[$code] ?? $code,
            'count' => $count,
            'status' => 'idle',
            'label' => 'Tidak dipilih',
            'share_text' => null,
            'per_person_share' => null,
            'amount_text' => null,
            'per_person_amount_text' => null,
            'reason' => self::IDLE_HINT[$code] ?? 'Pilihan belum diisi.',
        ];
    }

    /** @return array{0: bool, 1: int} */
    private function selectedAndCount(string $code, array $heirs): array
    {
        return match ($code) {
            'husband' => [(bool) ($heirs['husband'] ?? false), 1],
            'wife' => [$code === 'wife' && (int) $heirs['wife_count'] > 0, (int) $heirs['wife_count']],
            'father', 'mother', 'maternal_grandmother', 'grandfather' => [(bool) $heirs[$code], 1],
            default => [((int) $heirs[$code]) > 0, (int) $heirs[$code]],
        };
    }

    /**
     * Klasifikasi asabah — hanya mengikuti kaidah distribusi mesin
     * (sama dengan label kartu hasil), tanpa menghitung bagian baru.
     *
     * @param  array<string, mixed>  $heirs
     */
    private function isAsabah(string $code, array $heirs): bool
    {
        return match ($code) {
            'son', 'grandson', 'brother' => true,
            'father' => (int) $heirs['sons'] === 0,
            'grandfather' => true,
            'daughter' => (int) $heirs['sons'] > 0,
            'granddaughter' => (int) $heirs['grandsons'] > 0,
            'sister' => (int) $heirs['brothers'] > 0
                || (int) $heirs['daughters'] > 0
                || (int) $heirs['granddaughters'] > 0,
            default => false,
        };
    }

    /** @param  array<string, mixed>  $heirs */
    private function reason(string $code, array $heirs, array $share, bool $asabah): string
    {
        $text = $share['text'];

        return match ($code) {
            'husband' => (str_starts_with($text, '1/2'))
                ? 'Tidak ada keturunan yang mewarisi, jadi bagian suami 1/2.'
                : 'Ada keturunan yang memengaruhi bagian, jadi bagian suami berkurang menjadi '.$text.'.',
            'wife' => (str_starts_with($text, '1/4'))
                ? 'Tidak ada keturunan yang mewarisi; bagian kolektif seluruh istri 1/4.'
                : 'Ada keturunan yang mewarisi; bagian kolektif seluruh istri '.$text.'.',
            'father' => $asabah
                ? 'Bagian fard 1/6 ditambah seluruh sisa harta sebagai asabah (tidak ada anak laki-laki).'
                : 'Mendapat 1/6 sebagai bagian ashabul furudh.',
            'mother' => (str_starts_with($text, '1/3'))
                ? 'Mendapat 1/3 karena tidak ada keturunan dan tidak ada dua saudara atau lebih.'
                : 'Mendapat 1/6 karena ada keturunan (atau menurut aturan kasus khusus faraid dua saudara ke atas).',
            'grandfather' => 'Pengganti ayah: bagian total '.$text.' — sisa diwariskan sebagai asabah bila tidak ada ayah.',
            'maternal_grandmother' => 'Mendapat 1/6.'.($share['disputed'] ? ' Bersama ibu: kasus gharawain (beda pendapat).' : ''),
            'son' => $asabah
                ? ('Menerima sisa harta (asabah).'
                    . ((int) $heirs['daughters'] > 0 ? ' Bersama anak perempuan mengikuti rasio 2 : 1.' : ''))
                : 'Belum relevan untuk mesin saat ini.',
            'daughter' => $asabah
                ? 'Bersama anak laki-laki, bagian asabah dengan rasio 1 : 2 (anak perempuan sepertiga anak laki-laki).'
                : 'Tidak ada anak laki-laki, jadi kelompok anak perempuan menerima '.$text.' (sisa diradd-kan bila ada fard lain).',
            'grandson' => ((int) $heirs['granddaughters'] > 0)
                ? 'Asabah pengganti ayahnya — bersama cucu perempuan rasio 2 : 1.'
                : 'Asabah pengganti ayahnya yang lebih dulu wafat.',
            'granddaughter' => $asabah
                ? 'Bersama cucu laki-laki, bagian asabah dengan rasio 1 : 2.'
                : 'Tidak ada cucu laki-laki, jadi kelompok cucu perempuan menerima '.$text.' (sisa diradd-kan bila ada fard lain).',
            'brother' => 'Asabah — menerima sisa harta setelah bagian fard dibagikan.',
            'sister' => $asabah
                ? (((int) $heirs['brothers'] > 0)
                    ? 'Asabah bil ghair — bersama saudara laki-laki rasio 1 : 2.'
                    : 'Asabah ma\'al ghair — menyerap sisa bersama anak perempuan.')
                : 'Tidak ada saudara laki-laki; kelompok saudara perempuan menerima '.$text.'.',
            default => 'Mendapat bagian menurut ketentuan faraid.',
        };
    }
}
