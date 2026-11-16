<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeirSeeder extends Seeder
{
    /**
     * Master ahli waris. Isi setiap kartu merujuk dalil: QS. An-Nisaa 4:11–12
     * & 4:176, Kompilasi Hukum Islam (Inpres 1/1991) pasal yang bersangkutan,
     * dan kitab Fathul-Qarib (Ibnu Qasim al-Ghazi, Bab al-Faraidh).
     */
    public function run(): void
    {
        $sourceKhi = Source::where('code', 'KHI')->value('id');

        $heirs = [
            // Golongan fard (dzawil furudh) — memiliki bagian pasti
            ['code' => 'husband', 'name_id' => 'Suami', 'name_ar' => 'az-zauj', 'slug' => 'suami', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '2', 'detail' => 'Dzawil furudh: 1/2 bila pewaris tidak memiliki keturunan, 1/4 bila ada keturunan (QS. An-Nisaa 4:12; KHI Pasal 179). Setelah jatahnya terbayar, ia paling utama menerima sisa harta sebagai ashabah bil baqiyyah (Fathul-Qarib, Bab al-Faraidh).'],
            ['code' => 'wife', 'name_id' => 'Istri', 'name_ar' => 'az-zaujah', 'slug' => 'istri', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '4', 'detail' => 'Maksimum 1/4 sebagai golongan: satu istri mendapat 1/4 tanpa keturunan almarhum, 1/8 bila ada keturunan; empat istri membagi rata jatah golongan tersebut. (Pembagian 1/2 adalah bagian SUAMI, bukan istri.)'],
            ['code' => 'son', 'name_id' => 'Anak laki-laki', 'name_ar' => 'al-ibn', 'slug' => 'anak-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Ashabah binafsih paling dekat: menerima seluruh sisa harta setelah jatah dzawil furudh terbayar. Bersama anak perempuan, bagian keduanya dibagi nisbah 2 : 1 (QS. An-Nisaa 4:11; KHI Pasal 176).'],
            ['code' => 'daughter', 'name_id' => 'Anak perempuan', 'name_ar' => 'al-bint', 'slug' => 'anak-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Dzawil furudh: sendirian 1/2; dua atau lebih 2/3 dibagi sama. Bila bersama anak laki-laki, ia menjadi ashabah bil ghair — bagian pastinya gugur dan dibagi nisbah 2 : 1 (laki-laki : perempuan).'],
            ['code' => 'father', 'name_id' => 'Ayah', 'name_ar' => 'al-ab', 'slug' => 'ayah', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Dzawil furudh sekaligus ashabah: mendapat 1/6 bila pewaris memiliki anak; bila tidak ada anak laki-laki ia juga menerima sisa harta. Bersama anak laki-laki ia ashabah binafsih. Kehadirannya menghalangi kakek dan seluruh saudara pewaris (QS. An-Nisaa 4:11; KHI Pasal 177).'],
            ['code' => 'mother', 'name_id' => 'Ibu', 'name_ar' => 'al-umm', 'slug' => 'ibu', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Mendapat 1/6 bila pewaris memiliki keturunan atau saudara yang banyak, dan 1/3 bila tidak keduanya (QS. An-Nisaa 4:11; KHI Pasal 178). Pada masalah maasyul (umariyyatan) ia menerima 1/3 sisa setelah jatah suami/istri terbayar (Fathul-Qarib, Bab al-Faraidh).'],
            ['code' => 'grandfather_father', 'name_id' => 'Kakek dari ayah', 'name_ar' => 'al-jadd', 'slug' => 'kakek-ayah', 'gender' => 'm', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Menggantikan ayah bila ayah wafat lebih dulu: mendapat 1/6 bila tidak ada anak laki-laki pewaris, dan dapat ashabah menerima sisa. Pada bentrok dengan saudara pewaris, para ahli faraid menetapkan bagian terbesarnya (mushal/mushtarakah) (Fathul-Qarib, Bab al-Faraidh).'],
            ['code' => 'grandmother_mother', 'name_id' => 'Nenek dari ibu', 'name_ar' => 'al-jaddah', 'slug' => 'nenek-ibu', 'gender' => 'f', 'category' => 'fard', 'max_n' => '1', 'max_d' => '6', 'detail' => 'Dzawil furudh bila ibu tidak ada: mendapat 1/6 (KHI mengatur jatahnya bersama jatah ibu). Nenek yang lebih dekat menghalangi nenek yang lebih jauh pada arah ibu (Fathul-Qarib, Bab al-Faraidh).'],
            ['code' => 'sibling_male_full', 'name_id' => 'Saudara sedarah laki-laki', 'name_ar' => 'al-akh li-abawih', 'slug' => 'saudara-sedarah-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Ashabah binafsih (menerima sisa) bila tidak ada ayah/anak laki-laki pewaris; tidak memiliki bagian pasti 2/3 — angka 2/3 berlaku untuk SAUDARA PEREMPUAN sedarah pada kalalah (QS. An-Nisaa: 176).'],
            ['code' => 'sister_female_full', 'name_id' => 'Saudara sedarah perempuan', 'name_ar' => 'al-ukht li-abawih', 'slug' => 'saudara-sedarah-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Dzawil furudh pada kalalah: sendirian 1/2; dua orang atau lebih 2/3 dibagi sama (QS. An-Nisaa 4:176; KHI Pasal 182). Bersama saudara sedarah laki-laki ia ashabah bil ghair, dibagi nisbah 2 : 1.'],
            // Golongan arham (dzawil arham) — tidak ada bagian pasti di Tariq Ahli Waris selain KHI
            ['code' => 'daughter_of_son', 'name_id' => 'Cucu perempuan (dari anak laki-laki)', 'name_ar' => 'bint al-ibn', 'slug' => 'cucu-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Bint al-ibn. Saat pewaris tidak memiliki anak laki-laki, ia menerima jatah seperti anak perempuan dengan takhsis (dirapatkan bersama ashabah); bila ayahnya wafat lebih dulu, ia mewakili ayahnya (KHI Pasal 185 tentang kewakilan; jatah pada KHI Pasal 182).'],
            ['code' => 'grandson', 'name_id' => 'Cucu laki-laki (dari anak laki-laki)', 'name_ar' => 'ibn al-ibn', 'slug' => 'cucu-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Ibn al-ibn: ashabah binafsih bila pewaris tidak memiliki anak laki-laki; bila ayahnya wafat lebih dulu ia mewakili ayahnya (KHI Pasal 185). Bersama putri pewaris dibagi nisbah 2 : 1 (QS. An-Nisaa 4:11; KHI Pasal 176).'],
            ['code' => 'brother_paternal', 'name_id' => 'Saudara seayah laki-laki', 'name_ar' => 'al-akh li-abih', 'slug' => 'saudara-seayah-laki', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Al-akh li-abih: ashabah binafsih bila tidak ada ayah, anak laki-laki, dan saudara sedarah laki-laki (ia terhalang oleh keduanya). Pada kalalah ia berbagi bersama saudari seayahnya nisbah 2 : 1 (QS. An-Nisaa 4:176; KHI Pasal 182; Fathul-Qarib).'],
            ['code' => 'sister_paternal', 'name_id' => 'Saudara seayah perempuan', 'name_ar' => 'al-ukht li-abih', 'slug' => 'saudara-seayah-perempuan', 'gender' => 'f', 'category' => 'fard', 'max_n' => '2', 'max_d' => '3', 'detail' => 'Al-ukht li-abih: terhalang saudara sedarah laki-laki dan (menurut fiqh klasik) oleh dua saudara sedarah perempuan atau lebih. Pada kalalah ia menerima jatah bersama saudara seayah laki-laki nisbah 2 : 1 (QS. An-Nisaa 4:176; KHI Pasal 182).'],
            ['code' => 'uncle_paternal', 'name_id' => 'Paman seayah', 'name_ar' => 'al-amm', 'slug' => 'paman-seayah', 'gender' => 'm', 'category' => 'asabah', 'max_n' => null, 'max_d' => null, 'detail' => 'Al-amm li-abih: ashabah binafsih dari kelas saudara ayah — menerima sisa harta bila tidak ada ahli waris yang lebih dekat, dan menghalangi saudara ayah serta sepupunya (Fathul-Qarib, Bab al-Faraidh).'],
            ['code' => 'cousin_paternal_son', 'name_id' => 'Sepupu dari paman seayah', 'name_ar' => 'ibn al-amm', 'slug' => 'sepupu-paman', 'gender' => 'm', 'category' => 'arham', 'max_n' => null, 'max_d' => null, 'detail' => 'Ibn al-amm (anak paman seayah): ashabah binafsih setelah paman — ia menggantikan posisi ayahnya (paman) bila lebih dulu wafat, dan tidak mendapat apa-apa bila masih ada ahli waris yang lebih dekat (Fathul-Qarib, Bab al-Faraidh).'],
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
                    'detail' => $heir['detail'],
                    'source_id' => $sourceKhi,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
