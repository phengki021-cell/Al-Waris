<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeirSeeder extends Seeder
{
    /**
     * Master ahli waris umum. SEMUA data ini berlabel DEMO — rinciannya
     * akan diverifikasi bersama ahli faraid pada Tahap 2 sebelum dipakai publik.
     */
    public function run(): void
    {
        $sourceKhi = Source::where('code', 'KHI')->value('id');

        $heirs = [
            // Golongan fard (dzawil furudh) — memiliki bagian pasti
            ['code' => 'husband', 'name_id' => 'Suami', 'name_ar' => 'az-zauj', 'slug' => 'suami', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '2', 'detail' => 'Mendapat 1/2 jika istri tidak memiliki anak/keturunan; 1/4 jika ada anak/keturunan.'],
            ['code' => 'wife', 'name_id' => 'Istri', 'name_ar' => 'az-zaujah', 'slug' => 'istri', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '2', 'detail' => 'Istri (satu) mendapat 1/4 tanpa keturunan almarhum; 1/8 jika ada keturunan. Istri banyak membagi rata bagian tersebut.'],
            ['code' => 'son', 'name_id' => 'Anak laki-laki', 'name_ar' => 'al-ibn', 'slug' => 'anak-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Ashabah binafsih: menerima sisa harta; dua anak laki-laki membagi bersama anak perempuan (bagi rata 2:1).'],
            ['code' => 'daughter', 'name_id' => 'Anak perempuan', 'name_ar' => 'al-bint', 'slug' => 'anak-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Sendirian 1/2; dua atau lebih 2/3 dibagi sama.'],
            ['code' => 'father', 'name_id' => 'Ayah', 'name_ar' => 'al-ab', 'slug' => 'ayah', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => '1/6 tanpa anak laki-laki; bisa asabah bersama anak laki-laki.'],
            ['code' => 'mother', 'name_id' => 'Ibu', 'name_ar' => 'al-umm', 'slug' => 'ibu', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => '1/6 jika ada keturunan/saudara; 1/3 jika tidak ada keduanya; 1/3 sisanya pada masalah mas\'ul (umariyyatan).'],
            ['code' => 'grandfather_father', 'name_id' => 'Kakek dari ayah', 'name_ar' => 'al-jadd', 'slug' => 'kakek-ayah', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Menggantikan posisi ayah jika ayah sudah wafat.'],
            ['code' => 'grandmother_mother', 'name_id' => 'Nenek dari ibu', 'name_ar' => 'al-jaddah', 'slug' => 'nenek-ibu', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Memperoleh 1/6 apabila tidak ada ibu.'],
            ['code' => 'sibling_male_full', 'name_id' => 'Saudara sedarah laki-laki', 'name_ar' => 'al-akh li-abawih', 'slug' => 'saudara-sedarah-laki', 'gender' => 'm', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Pada kasus kalalah (QS. An-Nisaa: 176); asabah jika tidak ada ayah/anak laki-laki.'],
            ['code' => 'sister_female_full', 'name_id' => 'Saudara sedarah perempuan', 'name_ar' => 'al-ukht li-abawih', 'slug' => 'saudara-sedarah-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Sendiri 1/2; dua atau lebih 2/3 (kalalah).'],
            // Golongan arham (dzawil arham) — tidak ada bagian pasti di Tariq Ahli Waris selain KHI
            ['code' => 'daughter_of_son', 'name_id' => 'Cucu perempuan (dari anak laki-laki)', 'name_ar' => 'bint al-ibn', 'slug' => 'cucu-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Mendapat jatah ketika menggantikan ayahnya (perwakilan/siwah) yang tidak mendapat bagian standar.'],
            ['code' => 'grandson', 'name_id' => 'Cucu laki-laki (dari anak laki-laki)', 'name_ar' => 'ibn al-ibn', 'slug' => 'cucu-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Jadian asabah/update jika anak laki-laki langsung tidak ada.'],
            ['code' => 'brother_paternal', 'name_id' => 'Saudara seayah laki-laki', 'name_ar' => 'al-akh li-abih', 'slug' => 'saudara-seayah-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Bagian menurut golongan/pengganti (detail di Tahap 2).'],
            ['code' => 'sister_paternal', 'name_id' => 'Saudara seayah perempuan', 'name_ar' => 'al-ukht li-abih', 'slug' => 'saudara-seayah-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Bagian seperti saudara sedarah perempuan pada kasus tertentu.'],
            ['code' => 'uncle_paternal', 'name_id' => 'Paman seayah', 'name_ar' => 'al-amm', 'slug' => 'paman-seayah', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Asabah (keponakan) pada keluarga dhaba.'],
            ['code' => 'cousin_paternal_son', 'name_id' => 'Sepupu dari paman seayah', 'name_ar' => 'ibn al-amm', 'slug' => 'sepupu-paman', 'gender' => 'm', 'category' => 'arham', 'max_n' => null, 'max_d' => null, 'detail' => 'Golongan dzawil arham; metoda perhitungan di Tahap 2.'],
        ];

        foreach ($heirs as $heir) {
            DB::table('heirs')->updateOrInsert(
                ['code' => $heir['code']],
                [
                    'code' => $heir['code'],
                    'name_id' => $heir['name_id'],
                    'name_ar' => $heir['name_ar'],
                    'slug' => $heir['slug'],
                    'gender' => $heir['gender'],
                    'category' => $heir['category'],
                    'max_share_numerator' => $heir['max_n'],
                    'max_share_denominator' => $heir['max_d'],
                    'detail' => $heir['detail'].' [DATA DEMO — perlu verifikasi ahli faraid]',
                    'source_id' => $sourceKhi,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
