<?php

namespace App\Services\Civil;

/**
 * Analisis wasiat (testamentair). Tidak menghasilkan angka final.
 */
class CivilTestamentAnalyzer
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function analyze(array $data): array
    {
        $adaWasiat = (bool) ($data['pewaris']['wasiat'] ?? false);

        return [
            'ada_wasiat' => $adaWasiat,
            'status' => $adaWasiat ? 'NEEDS_LEGAL_REVIEW' : 'READY',
            'catatan' => $adaWasiat
                ? 'Pewaris meninggalkan wasiat. Pemeriksaan bentuk, syarat, batas legitime portie, dan pencabutan perlu diverifikasi (Pasal 875–912 KUHPerdata).'
                : 'Tidak ada wasiat. Pewarisan ab intestato berlaku.',
        ];
    }
}