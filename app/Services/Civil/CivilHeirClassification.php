<?php

namespace App\Services\Civil;

/**
 * Klasifikasi calon ahli waris ke dalam golongan KUHPerdata (ab intestato).
 * Hanya mengidentifikasi calon; tidak menghitung bagian final.
 */
class CivilHeirClassification
{
    /**
     * @param array<string,mixed> $data  data keluarga (pewaris, pasangan, anak, ayah, ibu, saudara)
     * @return array{calon: array, golongan: array, catatan: array}
     */
    public function classify(array $data): array
    {
        $calon = [];
        $catatan = [];

        $pewarisMeninggal = ! empty($data['pewaris']['tgl_meninggal'] ?? null);

        // Pasangan
        foreach ($data['pasangan'] ?? [] as $p) {
            if (($p['status'] ?? 'hidup') === 'meninggal') {
                $catatan[] = 'Pasangan ' . ($p['nama'] ?? '') . ' sudah meninggal sebelum pewaris; tidak menjadi ahli waris.';
                continue;
            }
            $calon[] = [
                'nama' => $p['nama'] ?? 'Pasangan',
                'hubungan' => 'pasangan',
                'golongan' => 'I',
                'status' => 'calon',
                'alasan' => 'Pasangan yang hidup terlama (Golongan I).',
            ];
        }

        // Anak
        $adaAnakHidup = false;
        foreach ($data['anak'] ?? [] as $a) {
            if (($a['status'] ?? 'hidup') === 'meninggal') {
                $catatan[] = 'Anak ' . ($a['nama'] ?? '') . ' meninggal lebih dahulu; kemungkinan penggantian tempat diperiksa terpisah.';
                continue;
            }
            $adaAnakHidup = true;
            $calon[] = [
                'nama' => $a['nama'] ?? 'Anak',
                'hubungan' => 'anak',
                'golongan' => 'I',
                'status' => 'calon',
                'alasan' => 'Keturunan langsung pewaris (Golongan I).',
            ];
        }

        // Orang tua
        $ayahHidup = ($data['ayah']['status'] ?? 'tidak_diketahui') === 'hidup';
        $ibuHidup = ($data['ibu']['status'] ?? 'tidak_diketahui') === 'hidup';

        // Jika ada keturunan hidup, orang tua tidak mewaris (Golongan I mengecualikan II)
        if (! $adaAnakHidup) {
            if ($ayahHidup) {
                $calon[] = ['nama' => $data['ayah']['nama'] ?? 'Ayah', 'hubungan' => 'ayah', 'golongan' => 'II', 'status' => 'calon', 'alasan' => 'Orang tua (Golongan II) karena tidak ada keturunan hidup.'];
            }
            if ($ibuHidup) {
                $calon[] = ['nama' => $data['ibu']['nama'] ?? 'Ibu', 'hubungan' => 'ibu', 'golongan' => 'II', 'status' => 'calon', 'alasan' => 'Orang tua (Golongan II) karena tidak ada keturunan hidup.'];
            }
        } else {
            if ($ayahHidup || $ibuHidup) {
                $catatan[] = 'Orang tua tidak mewaris karena ada keturunan hidup (Golongan I mengecualikan Golongan II).';
            }
        }

        // Saudara (hanya jika tidak ada keturunan dan tidak ada orang tua)
        if (! $adaAnakHidup && ! $ayahHidup && ! $ibuHidup) {
            foreach ($data['saudara'] ?? [] as $s) {
                if (($s['status'] ?? 'hidup') === 'meninggal') {
                    $catatan[] = 'Saudara ' . ($s['nama'] ?? '') . ' meninggal lebih dahulu; kemungkinan penggantian tempat diperiksa.';
                    continue;
                }
                $calon[] = [
                    'nama' => $s['nama'] ?? 'Saudara',
                    'hubungan' => 'saudara',
                    'golongan' => 'II',
                    'status' => 'calon',
                    'alasan' => 'Saudara (Golongan II) karena tidak ada keturunan dan orang tua.',
                ];
            }
        }

        // Tentukan golongan aktif
        $golonganAktif = 'I';
        if (empty(array_filter($calon, fn ($c) => $c['golongan'] === 'I'))) {
            if (! empty(array_filter($calon, fn ($c) => $c['golongan'] === 'II'))) {
                $golonganAktif = 'II';
            } else {
                $golonganAktif = 'III/IV (perlu verifikasi)';
                $catatan[] = 'Golongan III/IV memerlukan pemeriksaan lebih lanjut (kakek/nenek, sanak saudara).';
            }
        }

        return [
            'calon' => $calon,
            'golonganAktif' => $golonganAktif,
            'catatan' => $catatan,
        ];
    }
}