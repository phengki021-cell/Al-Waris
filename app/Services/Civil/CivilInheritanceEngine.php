<?php

namespace App\Services\Civil;

/**
 * Pengatur proses analisis waris perdata. Tidak menghasilkan pembagian final
 * kecuali aturan VERIFIED dan data cukup.
 */
class CivilInheritanceEngine
{
    public function __construct(
        private CivilHeirClassification $classification,
        private CivilRepresentationAnalyzer $representation,
        private CivilEstateAnalyzer $estate,
        private CivilTestamentAnalyzer $testament,
        private CivilLegitimePortieAnalyzer $legitime,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public function analyze(array $data): CivilInheritanceResult
    {
        $result = new CivilInheritanceResult();

        // 1. Validasi data pewaris
        if (empty($data['pewaris']['nama'] ?? '')) {
            $result->status = 'NEEDS_MORE_DATA';
            $result->dataKurang[] = 'Nama pewaris belum diisi.';
        }

        // 2. Klasifikasi ahli waris
        $cls = $this->classification->classify($data);
        $result->calonAhliWaris = $cls['calon'];
        $result->golongan = ['aktif' => $cls['golonganAktif']];
        foreach ($cls['catatan'] as $c) {
            $result->catatan[] = $c;
        }

        // 3. Penggantian tempat
        $result->penggantianTempat = $this->representation->analyze($data);

        // 4. Harta
        $result->harta = $this->estate->analyze($data);
        if (($result->harta['status'] ?? '') === 'NEEDS_MORE_DATA') {
            $result->status = 'NEEDS_MORE_DATA';
            $result->dataKurang[] = 'Data harta peninggalan belum lengkap.';
        }

        // 5. Wasiat
        $result->wasiat = $this->testament->analyze($data);
        if (($result->wasiat['status'] ?? '') === 'NEEDS_LEGAL_REVIEW') {
            $result->status = 'NEEDS_LEGAL_REVIEW';
            $result->catatan[] = 'Terdapat wasiat — perlu pemeriksaan legitime portie dan batas wasiat.';
        }

        // 6. Legitime portie
        $result->legitime = $this->legitime->analyze($data);
        if (($result->legitime['status'] ?? '') === 'NEEDS_REVIEW') {
            if ($result->status === 'READY') {
                $result->status = 'NEEDS_LEGAL_REVIEW';
            }
            $result->catatan[] = $result->legitime['catatan'];
        }

        // Jika data cukup dan tidak ada review, status READY (belum CALCULATED karena aturan final belum diverifikasi)
        if ($result->status === 'READY') {
            $result->status = 'NEEDS_LEGAL_REVIEW';
            $result->catatan[] = 'Mesin perdata masih tahap analisis. Pembagian final belum tersedia (aturan perlu verifikasi).';
        }

        $result->aturan = [
            ['id' => 'CIV-001', 'nama' => 'Identifikasi calon ahli waris', 'status' => 'VERIFIED'],
            ['id' => 'CIV-002', 'nama' => 'Golongan ahli waris I', 'status' => 'NEEDS_REVIEW'],
            ['id' => 'CIV-003', 'nama' => 'Golongan ahli waris II-IV', 'status' => 'NEEDS_REVIEW'],
            ['id' => 'CIV-004', 'nama' => 'Penggantian tempat (plaatsvervulling)', 'status' => 'NEEDS_REVIEW'],
            ['id' => 'CIV-005', 'nama' => 'Legitime portie', 'status' => 'NOT_IMPLEMENTED'],
            ['id' => 'CIV-006', 'nama' => 'Wasiat (testament)', 'status' => 'NEEDS_REVIEW'],
            ['id' => 'CIV-007', 'nama' => 'Hibah dan inbreng', 'status' => 'NOT_IMPLEMENTED'],
            ['id' => 'CIV-008', 'nama' => 'Penerimaan/penolakan warisan', 'status' => 'NOT_IMPLEMENTED'],
            ['id' => 'CIV-009', 'nama' => 'Pembagian nominal', 'status' => 'NOT_IMPLEMENTED'],
        ];

        return $result;
    }
}