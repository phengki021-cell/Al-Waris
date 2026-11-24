<?php

namespace App\Services\Civil;

/**
 * Analisis penggantian tempat (plaatsvervulling) — hanya identifikasi, tidak menyimpulkan hak final.
 */
class CivilRepresentationAnalyzer
{
    /**
     * @param array<string,mixed> $data
     * @return array<int, array<string,mixed>>
     */
    public function analyze(array $data): array
    {
        $hasil = [];
        $anak = $data['anak'] ?? [];

        foreach ($anak as $a) {
            if (($a['status'] ?? 'hidup') !== 'meninggal') {
                continue;
            }
            $namaAnak = $a['nama'] ?? 'Anak';
            $cucu = array_filter($anak, fn ($x) => ($x['parent_id'] ?? null) !== null && (int) ($x['parent_id']) === (int) ($a['id'] ?? -1));
            // Fallback: cucu yang parent_id diset (sederhana)
            $hasil[] = [
                'orang_yang_digantikan' => $namaAnak,
                'status' => 'perlu_pemeriksaan',
                'catatan' => 'Anak meninggal lebih dahulu. Keturunannya (cucu) berpotensi menggantikan kedudukannya. Syarat hukum (plaatsvervulling) perlu diverifikasi terhadap KUHPerdata Pasal 838 dan fakta kasus.',
                'keturunan_terdaftar' => count($cucu),
            ];
        }

        if (empty($hasil)) {
            $hasil[] = [
                'orang_yang_digantikan' => null,
                'status' => 'tidak_ada',
                'catatan' => 'Tidak ada anggota keluarga yang meninggal lebih dahulu dari pewaris.',
                'keturunan_terdaftar' => 0,
            ];
        }

        return $hasil;
    }
}