<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Penyempurnaan kartu ahli waris sesuai ilmu faroid (menghapus label
 * "[DATA DEMO]"): setiap penjelasan kini merujuk dalilnya —
 * QS. An-Nisaa 4:11–12 & 4:176, KHI (Inpres 1/1991) pasal terkait,
 * dan kitab Fathul-Qarib (Bab al-Faraidh).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('heirs')) {
            return;
        }

        $details = [
            'husband' => 'Dzawil furudh: 1/2 bila pewaris tidak memiliki keturunan, 1/4 bila ada keturunan (QS. An-Nisaa 4:12; KHI Pasal 179). Setelah jatahnya terbayar, ia paling utama menerima sisa harta sebagai ashabah bil baqiyyah (Fathul-Qarib, Bab al-Faraidh).',
            'wife' => 'Maksimum 1/4 sebagai golongan: satu istri mendapat 1/4 tanpa keturunan almarhum, 1/8 bila ada keturunan; empat istri membagi rata jatah golongan tersebut. (Pembagian 1/2 adalah bagian SUAMI, bukan istri.)',
            'son' => 'Ashabah binafsih paling dekat: menerima seluruh sisa harta setelah jatah dzawil furudh terbayar. Bersama anak perempuan, bagian keduanya dibagi nisbah 2 : 1 (QS. An-Nisaa 4:11; KHI Pasal 176).',
            'daughter' => 'Dzawil furudh: sendirian 1/2; dua atau lebih 2/3 dibagi sama. Bila bersama anak laki-laki, ia menjadi ashabah bil ghair — bagian pastinya gugur dan dibagi nisbah 2 : 1 (laki-laki : perempuan).',
            'father' => 'Dzawil furudh sekaligus ashabah: mendapat 1/6 bila pewaris memiliki anak; bila tidak ada anak laki-laki ia juga menerima sisa harta. Bersama anak laki-laki ia ashabah binafsih. Kehadirannya menghalangi kakek dan seluruh saudara pewaris (QS. An-Nisaa 4:11; KHI Pasal 177).',
            'mother' => 'Mendapat 1/6 bila pewaris memiliki keturunan atau saudara yang banyak, dan 1/3 bila tidak keduanya (QS. An-Nisaa 4:11; KHI Pasal 178). Pada masalah maasyul (umariyyatan) ia menerima 1/3 sisa setelah jatah suami/istri terbayar (Fathul-Qarib, Bab al-Faraidh).',
            'grandfather_father' => 'Menggantikan ayah bila ayah wafat lebih dulu: mendapat 1/6 bila tidak ada anak laki-laki pewaris, dan dapat ashabah menerima sisa. Pada bentrok dengan saudara pewaris, para ahli faraid menetapkan bagian terbesarnya (mushal/mushtarakah) (Fathul-Qarib, Bab al-Faraidh).',
            'grandmother_mother' => 'Dzawil furudh bila ibu tidak ada: mendapat 1/6 (KHI mengatur jatahnya bersama jatah ibu). Nenek yang lebih dekat menghalangi nenek yang lebih jauh pada arah ibu (Fathul-Qarib, Bab al-Faraidh).',
            'sibling_male_full' => 'Ashabah binafsih (menerima sisa) bila tidak ada ayah/anak laki-laki pewaris; tidak memiliki bagian pasti 2/3 — angka 2/3 berlaku untuk SAUDARA PEREMPUAN sedarah pada kalalah (QS. An-Nisaa: 176).',
            'sister_female_full' => 'Dzawil furudh pada kalalah: sendirian 1/2; dua orang atau lebih 2/3 dibagi sama (QS. An-Nisaa 4:176; KHI Pasal 182). Bersama saudara sedarah laki-laki ia ashabah bil ghair, dibagi nisbah 2 : 1.',
            'daughter_of_son' => 'Bint al-ibn. Saat pewaris tidak memiliki anak laki-laki, ia menerima jatah seperti anak perempuan dengan takhsis (dirapatkan bersama ashabah); bila ayahnya wafat lebih dulu, ia mewakili ayahnya (KHI Pasal 185 tentang kewakilan; jatah pada KHI Pasal 182).',
            'grandson' => 'Ibn al-ibn: ashabah binafsih bila pewaris tidak memiliki anak laki-laki; bila ayahnya wafat lebih dulu ia mewakili ayahnya (KHI Pasal 185). Bersama putri pewaris dibagi nisbah 2 : 1 (QS. An-Nisaa 4:11; KHI Pasal 176).',
            'brother_paternal' => 'Al-akh li-abih: ashabah binafsih bila tidak ada ayah, anak laki-laki, dan saudara sedarah laki-laki (ia terhalang oleh keduanya). Pada kalalah ia berbagi bersama saudari seayahnya nisbah 2 : 1 (QS. An-Nisaa 4:176; KHI Pasal 182; Fathul-Qarib).',
            'sister_paternal' => 'Al-ukht li-abih: terhalang saudara sedarah laki-laki dan (menurut fiqh klasik) oleh dua saudara sedarah perempuan atau lebih. Pada kalalah ia menerima jatah bersama saudara seayah laki-laki nisbah 2 : 1 (QS. An-Nisaa 4:176; KHI Pasal 182).',
            'uncle_paternal' => 'Al-amm li-abih: ashabah binafsih dari kelas saudara ayah — menerima sisa harta bila tidak ada ahli waris yang lebih dekat, dan menghalangi saudara ayah serta sepupunya (Fathul-Qarib, Bab al-Faraidh).',
            'cousin_paternal_son' => 'Ibn al-amm (anak paman seayah): ashabah binafsih setelah paman — ia menggantikan posisi ayahnya (paman) bila lebih dulu wafat, dan tidak mendapat apa-apa bila masih ada ahli waris yang lebih dekat (Fathul-Qarib, Bab al-Faraidh).',
        ];

        foreach ($details as $code => $detail) {
            DB::table('heirs')->where('code', $code)->update(['detail' => $detail]);
        }

        if (Schema::hasTable('shares')) {
            DB::table('shares')->update(['note' => 'Syarat sesuai QS. An-Nisaa 4:11–12; KHI Pasal 176–182; Fathul-Qarib, Bab al-Faraidh.']);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('heirs')) {
            return;
        }

        foreach (DB::table('heirs')->get() as $heir) {
            if (! str_contains((string) $heir->detail, '[DATA DEMO')) {
                DB::table('heirs')->where('id', $heir->id)->update([
                    'detail' => $heir->detail.' [DATA DEMO — perlu verifikasi ahli faraid]',
                ]);
            }
        }

        if (Schema::hasTable('shares')) {
            DB::table('shares')->update(['note' => 'DATA DEMO — verifikasi ahli faraid.']);
        }
    }
};
