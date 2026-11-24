<?php

namespace App\Services\Civil;

/**
 * Analisis harta peninggalan dan kewajiban. Tidak menghasilkan angka final.
 */
class CivilEstateAnalyzer
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function analyze(array $data): array
    {
        $harta = $data['pewaris']['harta'] ?? '';
        $utang = $data['pewaris']['utang'] ?? '';

        return [
            'harta_peninggalan' => $harta,
            'utang_kewajiban' => $utang,
            'catatan' => 'Pisahkan harta bersama (gono-gini) dari harta pribadi pewaris sebelum perhitungan. Utang dibayar dari harta peninggalan. Detail perhitungan nominal memerlukan verifikasi aturan (Pasal 976–1003 KUHPerdata).',
            'status' => (trim($harta) === '' && trim($utang) === '') ? 'NEEDS_MORE_DATA' : 'READY',
        ];
    }
}