<?php

namespace App\Services\Civil;

/**
 * Hasil analisis waris perdata. Tidak menghasilkan angka final
 * kecuali aturan yang terlibat berstatus VERIFIED dan data cukup.
 */
class CivilInheritanceResult
{
    public string $status = 'READY'; // READY|CALCULATED|NEEDS_MORE_DATA|NEEDS_LEGAL_REVIEW|BLOCKED
    /** @var array<int, array<string,mixed>> */
    public array $calonAhliWaris = [];
    /** @var array<string, mixed> */
    public array $golongan = [];
    /** @var array<int, array<string,mixed>> */
    public array $penggantianTempat = [];
    /** @var array<string, mixed> */
    public array $harta = [];
    /** @var array<string, mixed> */
    public array $wasiat = [];
    /** @var array<string, mixed> */
    public array $legitime = [];
    /** @var array<int, string> */
    public array $catatan = [];
    /** @var array<int, string> */
    public array $dataKurang = [];
    /** @var array<int, array<string,mixed>> */
    public array $aturan = [];

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'calonAhliWaris' => $this->calonAhliWaris,
            'golongan' => $this->golongan,
            'penggantianTempat' => $this->penggantianTempat,
            'harta' => $this->harta,
            'wasiat' => $this->wasiat,
            'legitime' => $this->legitime,
            'catatan' => $this->catatan,
            'dataKurang' => $this->dataKurang,
            'aturan' => $this->aturan,
        ];
    }
}