<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Master sumber/referensi. Seluruh data berlabel DEMO (ShareSeeder/detail).
     */
    public function run(): void
    {
        $rows = [
            [
                'code' => 'QURAN-4-11',
                'title' => 'QS. An-Nisaa ayat 11',
                'type' => 'quran',
                'reference' => 'QS. An-Nisaa: 11',
                'excerpt' => 'Pengaturan bagian anak laki-laki, anak perempuan, serta kedua orang tua (lihat teks aslinya untuk keterperinciannya).',
                'is_primary' => true,
            ],
            [
                'code' => 'QURAN-4-12',
                'title' => 'QS. An-Nisaa ayat 12',
                'type' => 'quran',
                'reference' => 'QS. An-Nisaa: 12',
                'excerpt' => 'Bagian suami, istri, dan saudara sedarah...',
                'is_primary' => true,
            ],
            [
                'code' => 'QURAN-4-176',
                'title' => 'QS. An-Nisaa ayat 176 (kalalah)',
                'type' => 'quran',
                'reference' => 'QS. An-Nisaa: 176',
                'excerpt' => 'Perincian bagian saudara, keponakan, dan keluarga dhaba (kalalah).',
                'is_primary' => true,
            ],
            [
                'code' => 'QURAN-2-180',
                'title' => 'QS. Al-Baqarah ayat 180 (wasiat)',
                'type' => 'quran',
                'reference' => 'QS. Al-Baqarah: 180',
                'excerpt' => 'Dianjurkan mengurus wasiat untuk orang tua dan kerabat dengan batas yang mā\u02bf rūf.',
                'is_primary' => false,
            ],
            [
                'code' => 'KHI',
                'title' => 'Kompilasi Hukum Islam (KHI)',
                'type' => 'kompilasi',
                'reference' => 'Inpres No. 1 Tahun 1991',
                'excerpt' => 'Buku II: Hukum Waris; Pasal 171, 174-193 (bagian-bagian ahli waris).',
                'is_primary' => true,
            ],
            [
                'code' => 'KITAB-FATHUL-QARIB',
                'title' => 'Kitab Fathul-Qarib (Ibnu Qasim al-Ghazi)',
                'type' => 'kitab',
                'reference' => 'Bab al-Faraidh',
                'excerpt' => 'Tabel-tabel kecil faraid (bagian yang disukai di pesantren).',
                'is_primary' => false,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('sources')->updateOrInsert(
                ['code' => $row['code']],
                $row + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
