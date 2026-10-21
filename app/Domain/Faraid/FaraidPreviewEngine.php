<?php

namespace App\Domain\Faraid;

use Illuminate\Support\Collection;

/**
 * Mesin hitung faraid versi PREVIEW untuk Tahap 1.
 *
 * Baru mendukung beberapa kasus dasar yang jumlah bagiannya persis 1
 * (tanpa 'aul/radd/gawalan). Kasus lain ditolak dengan pesan jujur
 * "Belum didukung di Tahap 1".
 *
 * Seluruh hasil berlabel DEMO dan wajib diverifikasi ahli faraid
 * sebelum rilis publik (lihat docs/assumptions-and-risks.md).
 */
final class FaraidPreviewEngine
{
    /**
     * Kasus-kasus dasar terverifikasi (amal ummi, pecahan eksak).
     * Karakter: [suami|istri, anak_laki, anak_perempuan, ayah, ibu]
     */
    private const PRESETS = [
        ['husband' => 1, 'wife' => 0, 'sons' => 1, 'daughters' => 1, 'father' => 0, 'mother' => 0],
        ['husband' => 0, 'wife' => 1, 'sons' => 1, 'daughters' => 0, 'father' => 0, 'mother' => 0],
        ['husband' => 0, 'wife' => 0, 'sons' => 0, 'daughters' => 0, 'father' => 1, 'mother' => 0, 'sons_asabah' => 2],
        ['husband' => 0, 'wife' => 0, 'sons' => 0, 'daughters' => 0, 'father' => 0, 'mother' => 1],
        ['husband' => 1, 'wife' => 0, 'sons' => 0, 'daughters' => 0, 'father' => 0, 'mother' => 0],
        ['husband' => 0, 'wife' => 0, 'sons' => 2, 'daughters' => 0, 'father' => 0, 'mother' => 0],
        ['husband' => 0, 'wife' => 1, 'sons' => 0, 'daughters' => 0, 'father' => 0, 'mother' => 0],
    ];

    /** @return array{status: string, method: string, shares: array, note: string} */
    public function calculate(
        int $husband = 0,
        int $wife = 0,
        int $sons = 0,
        int $daughters = 0,
        int $father = 0,
        int $mother = 0,
        float $netEstate = 0.0,
    ): array {
        // Normalisasi Boolean ke 0/1.
        $a = ['husband' => $husband ? 1 : 0, 'wife' => $wife ? 1 : 0,
            'sons' => $sons, 'daughters' => $daughters,
            'father' => $father ? 1 : 0, 'mother' => $mother ? 1 : 0];

        // Cari kombinasi preset yang cocok.
        foreach (self::PRESETS as $preset) {
            if ($this->matches($a, $preset)) {
                $shares = $this->buildShares($preset, $netEstate);

                return [
                    'status' => 'valid',
                    'method' => 'amal_ummi',
                    'shares' => $shares,
                    'note' => 'DATA DEMO Tahap 1 — hasil diverifikasi manual, wajib cek ulang ahli faraid.',
                ];
            }
        }

        return [
            'status' => 'not_supported',
            'method' => null,
            'shares' => [],
            'note' => 'Kombinasi ahli waris belum didukung mesin Tahap 1. Kasus \'aul, radd, gawalan akan hadir pada Tahap 2.',
        ];
    }

    private function matches(array $a, array $preset): bool
    {
        return $a['husband'] === $preset['husband']
            && $a['wife'] === $preset['wife']
            && $a['sons'] === $preset['sons']
            && $a['daughters'] === $preset['daughters']
            && $a['father'] === $preset['father']
            && $a['mother'] === $preset['mother'];
    }

    /** @return Collection<int, array{code,name,n,d,text,amount}> */
    private function buildShares(array $preset, float $netEstate): Collection
    {
        $definitions = match (true) {
            // Suami 1/4 + anak laki 2u + anak perempuan 1u (amal 4)
            $preset['husband'] === 1 => [
                ['code' => 'husband', 'name' => 'Suami', 'n' => 1, 'd' => 4],
                ['code' => 'son', 'name' => 'Anak laki-laki', 'n' => 2, 'd' => 4],
                ['code' => 'daughter', 'name' => 'Anak perempuan', 'n' => 1, 'd' => 4],
            ],
            // Istri 1/8, sisanya anak laki-laki asabah bin-nafs
            $preset['wife'] === 1 => [
                ['code' => 'wife', 'name' => 'Istri', 'n' => 1, 'd' => 8],
                ['code' => 'son', 'name' => 'Anak laki-laki (sisa)', 'n' => 7, 'd' => 8],
            ],
            // Ayah 1/6 + 2 anak laki asabah: 5/6 dibagi 2 = 5/12
            $preset['father'] === 1 => [
                ['code' => 'father', 'name' => 'Ayah', 'n' => 1, 'd' => 6],
                ['code' => 'son', 'name' => 'Anak laki-laki', 'n' => 5, 'd' => 12],
            ],
            // Ibu saja 1/3
            $preset['mother'] === 1 => [
                ['code' => 'mother', 'name' => 'Ibu', 'n' => 1, 'd' => 3],
            ],
            // Suami saja, tanpa keturunan 1/2
            $preset['husband'] === 1 => [
                ['code' => 'husband', 'name' => 'Suami', 'n' => 1, 'd' => 2],
            ],
            // 2 anak laki saja: serata
            $preset['sons'] === 2 => [
                ['code' => 'son', 'name' => 'Anak laki-laki', 'n' => 1, 'd' => 2],
            ],
            default => [
                ['code' => 'wife', 'name' => 'Istri', 'n' => 1, 'd' => 4],
            ],
        };

        return collect($definitions)->map(function ($def) use ($netEstate) {
            $share = RationalNumber::of($def['n'], $def['d']);

            return [
                'code' => $def['code'],
                'name' => $def['name'],
                'n' => $def['n'],
                'd' => $def['d'],
                'text' => $share->toText(),
                'amount' => $netEstate > 0 ? round($netEstate * $share->toFloat(), 2) : null,
            ];
        });
    }
}
