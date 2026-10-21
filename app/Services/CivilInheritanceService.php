<?php

namespace App\Services;

/**
 * Modul edukasi Hukum Waris Perdata (KUHPerdata) — mode OPSIONAL pembanding
 * pada halaman Hitung Warisan. Kelas ini berdiri terpisah dan TIDAK
 * menyentuh mesin faraid (App\Domain\Faraid), sehingga kalkulator waris
 * Islam utama tetap seratus persen seperti semula.
 *
 * Model yang dipakai (model sederhana yang umum diajarkan, berlabel DEMO):
 * - Golongan I: pasangan hidup terlama + anak-anak sah (KUHPerdata Pasal 852),
 *   setiap satuan mendapat BAGIAN SETARA (tanpa beda anak laki/perempuan).
 * - Anak yang lebih dulu wafat digantikan cabang keturunannya (cucu) dihitung
 *   SATU bagian per cabang; di dalam cabang, cucu-cucu membagi sama rata
 *   (penggantian tempat, KUHPerdata Pasal 853).
 * - Pembulatan mengikuti kebiasaan aplikasi: dibulatkan ke bawah ke sen,
 *   sisa sen dibagikan rata satu sen demi satu sen sesuai urutan daftar.
 */
final class CivilInheritanceService
{
    /**
     * Hitung pembagian Golongan I model edukasi di atas.
     *
     * @param  array{estate?: float|string, debts?: float|string, funeral?: float|string, bequest?: float|string, spouse?: bool, children?: int|string, branches?: int|string, grandchildren?: int|string}  $input
     * @return array{status: 'valid'|'error', notes: list<string>, entries: list<array<string, mixed>>, pasals: list<array<string, string>>, net_cents: int, total_cents: int, parts: int}
     */
    public function calculate(array $input): array
    {
        $estate = (float) ($input['estate'] ?? 0);
        $debts = (float) ($input['debts'] ?? 0);
        $funeral = (float) ($input['funeral'] ?? 0);
        $bequest = (float) ($input['bequest'] ?? 0);

        /** @var list<array<string, string>> $pasals */
        $pasals = [];
        foreach ((array) config('furudh-dalil.kuhperdata.pasal', []) as $entry) {
            if (is_array($entry) && is_string($entry['label'] ?? null) && is_string($entry['teks'] ?? null)) {
                $pasals[] = [
                    'label' => $entry['label'],
                    'teks' => $entry['teks'],
                    'catatan' => (string) ($entry['catatan'] ?? ''),
                ];
            }
        }

        $notes = [
            'Hasil berlabel DEMO — model pembagian Golongan I saja (Pasal 852 & 853); perkara nyata wajib diserahkan kepada notaris/ahli hukum.',
            'Berbeda dengan faraid: bagian anak laki-laki dan anak perempuan di Golongan I adalah SAMA BESAR (bagian setara Pasal 852).',
            'Hak pasangan perkawinan kedua dibatasi (Pasal 852 huruf b): tidak lebih dari bagian anak sah terkecil dari perkawinan pertama — cek kondisi keluarga terkait.',
        ];

        if (empty($input['spouse']) === false && $this->toInt($input['children'] ?? 0) + $this->toInt($input['branches'] ?? 0) === 0) {
            $notes[] = 'Hanya pasangan yang hidup: dalam model bagian setara ini pasangan menerima seluruh harta bersih (Pasal 852); kasus tanpa keturunan pada praktik nyata dapat menyangkut Golongan II — minta penilaian ahli hukum.';
        }

        foreach (['estate' => $estate, 'debts' => $debts, 'funeral' => $funeral, 'bequest' => $bequest] as $key => $value) {
            if ($value < 0) {
                return $this->error("Nilai '$key' tidak boleh negatif.", $pasals);
            }
        }

        if ($estate === 0.0) {
            return $this->error('Nilai harta peninggalan masih kosong.', $pasals);
        }

        $netEstate = $estate - $debts - $funeral - $bequest;

        if ($netEstate < 0) {
            return $this->error('Kewajiban (hutang + biaya pengurusan + wasiat) melebihi harta yang ditinggalkan.', $pasals);
        }

        $spouse = ! empty($input['spouse']);
        $children = $this->toInt($input['children'] ?? 0);
        $branches = $this->toInt($input['branches'] ?? 0);
        $grandchildren = $this->toInt($input['grandchildren'] ?? 0);

        $parts = ($spouse ? 1 : 0) + $children + $branches;

        if ($parts === 0) {
            return $this->error('Minimal satu ahli waris Golongan I harus diisi: pasangan yang masih hidup, anak, atau cabang keturunan anak yang wafat.', $pasals);
        }

        if ($grandchildren === 0 && $branches > 0) {
            $notes[] = 'Cabang anak yang wafat terdaftar tanpa jumlah cucu — cabang ditagih sebagai satu satuan tanpa pemecah ke cucu; isi "Total cucu" bila ingin melihat bagian per cucu.';
        }

        $netCents = (int) round($netEstate * 100);
        $shareCents = intdiv($netCents, $parts);
        $remainder = $netCents - $shareCents * $parts;

        /** @var list<array<string, mixed>> $entries */
        $entries = [];

        if ($spouse) {
            $entries[] = [
                'kind' => 'person',
                'name' => 'Pasangan yang masih hidup',
                'count' => 1,
                'total_cents' => $shareCents,
                'per_person_cents' => $shareCents,
                'unit_text' => "1/{$parts}",
                'rujukan' => 'KUHPerdata Pasal 852 — bagian setara',
            ];
        }

        if ($children > 0) {
            $entries[] = [
                'kind' => 'group',
                'name' => 'Anak yang masih hidup',
                'count' => $children,
                'total_cents' => $shareCents * $children,
                'per_person_cents' => $shareCents,
                'unit_text' => "1/{$parts}",
                'rujukan' => 'KUHPerdata Pasal 852 — bagian setara per anak',
            ];
        }

        // Cucu dibagi imbang ke seluruh cabang (sisa cucu masuk cabang awal).
        $cucuPerCabang = [];
        if ($branches > 0) {
            $base = intdiv($grandchildren, $branches);
            $sisa = $grandchildren % $branches;

            for ($i = 0; $i < $branches; $i++) {
                $cucuPerCabang[] = $base + ($i < $sisa ? 1 : 0);
            }
        }

        foreach ($cucuPerCabang as $index => $jumlahCucu) {
            $entry = [
                'kind' => 'family',
                'name' => 'Cabang anak yang wafat — '.($index + 1),
                'count' => max(1, $jumlahCucu),
                'total_cents' => $shareCents,
                'per_person_cents' => $jumlahCucu > 0 ? (int) floor($shareCents / $jumlahCucu) : $shareCents,
                'unit_text' => "1/{$parts}",
                'rujukan' => 'KUHPerdata Pasal 853 — penggantian tempat (representasi) per cabang',
            ];

            if ($jumlahCucu > 1) {
                $entry['catatan'] = "Cucu-cucu di cabang ini ({$jumlahCucu} orang) membagi bagian cabang sama rata; pembulatan per orang ke bawah.";
            }

            $entries[] = $entry;
        }

        // Sisa sen dibagikan rata satu sen demi satu sen mengikuti urutan daftar.
        if ($remainder > 0 && $entries !== []) {
            $index = 0;

            while ($remainder > 0) {
                $entries[$index % count($entries)]['total_cents']++;
                $remainder--;
                $index++;
            }

            $notes[] = 'Pembulatan: setiap bagian dibulatkan ke bawah ke sen; sisa sen dibagikan rata satu per satu pada daftar ahli waris agar total tetap konsisten.';
        }

        foreach ($entries as $i => $entry) {
            $entries[$i]['per_person_cents'] = $entry['count'] > 0
                ? (int) floor($entry['total_cents'] / $entry['count'])
                : 0;
        }

        $total = array_sum(array_column($entries, 'total_cents'));

        // Keamanan (defense in depth): nominal yang ditampilkan tidak boleh
        // melenceng dari harta bersih; jika toh terjadi, kembalikan error
        // alih-alih menampilkan angka yang salah.
        if ($total !== $netCents) {
            return $this->error('Verifikasi total gagal: nominal tidak konsisten dengan harta bersih.', $pasals);
        }

        if ($debts > 0 || $funeral > 0 || $bequest > 0) {
            $notes[] = 'Harta dibagi dalam model ini adalah harta bersih setelah dipotong hutang, biaya pengurusan, dan wasiat/hibah — tanpa batas sepertiga versi faraid; ingat pembatasan "bagian sah" para ahli waris dalam Buku II KUHPerdata.';
        }

        return [
            'status' => 'valid',
            'notes' => $notes,
            'entries' => $entries,
            'pasals' => $pasals,
            'net_cents' => $netCents,
            'total_cents' => $total,
            'parts' => $parts,
        ];
    }

    /** Normalisasi input bilangan bulat (string kosong/kosong → 0). @return int */
    private function toInt(mixed $value): int
    {
        $value = is_string($value) ? trim($value) : $value;

        if ($value === '' || $value === null) {
            return 0;
        }

        return (int) $value;
    }

    /**
     * Struktur hasil yang konsisten untuk kasus gagal.
     *
     * @param  list<array<string, string>>  $pasals
     * @return array{status: 'error', notes: list<string>, entries: list<array<string, mixed>>, pasals: list<array<string, string>>, net_cents: int, total_cents: int, parts: int}
     */
    private function error(string $message, array $pasals): array
    {
        return [
            'status' => 'error',
            'notes' => [$message],
            'entries' => [],
            'pasals' => $pasals,
            'net_cents' => 0,
            'total_cents' => 0,
            'parts' => 0,
        ];
    }
}
