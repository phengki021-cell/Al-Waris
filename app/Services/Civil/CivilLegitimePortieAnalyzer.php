<?php

namespace App\Services\Civil;

/**
 * Analisis legitime portie. Status NEEDS_REVIEW karena persentase dan
 * dasar penghitungan belum diverifikasi.
 */
class CivilLegitimePortieAnalyzer
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function analyze(array $data): array
    {
        return [
            'status' => 'NEEDS_REVIEW',
            'catatan' => 'Legitime portie (bagian mutlak) memerlukan verifikasi pasal dan persentase sesuai susunan keluarga. Tidak ada angka final yang dikeluarkan.',
        ];
    }
}