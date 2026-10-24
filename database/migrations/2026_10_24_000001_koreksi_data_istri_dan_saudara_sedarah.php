<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Koreksi data edukasi faraid (keakuratan fiqih):
 * - Istri: bagian maksimum golongan adalah 1/4 (1/8 bila ada keturunan).
 * - Saudara sedarah laki-laki: ashabah (tanpa bagian pasti 2/3).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('heirs')) {
            DB::table('heirs')
                ->where('code', 'wife')
                ->update([
                    'max_share_numerator' => 1,
                    'max_share_denominator' => 4,
                    'detail' => 'Maksimum 1/4 sebagai golongan: satu istri mendapat 1/4 tanpa keturunan almarhum, 1/8 bila ada keturunan; empat istri membagi rata jatah golongan tersebut. (Pembagian 1/2 adalah bagian SUAMI, bukan istri.)',
                ]);

            DB::table('heirs')
                ->where('code', 'sibling_male_full')
                ->update([
                    'category' => 'asabah',
                    'max_share_numerator' => null,
                    'max_share_denominator' => null,
                    'detail' => 'Ashabah binafsih (menerima sisa) bila tidak ada ayah/anak laki-laki pewaris; tidak memiliki bagian pasti 2/3 — angka 2/3 berlaku untuk SAUDARA PEREMPUAN sedarah pada kalalah (QS. An-Nisaa: 176).',
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('heirs')) {
            DB::table('heirs')->where('code', 'wife')->update(['max_share_numerator' => 1, 'max_share_denominator' => 2]);
            DB::table('heirs')->where('code', 'sibling_male_full')->update([
                'category' => 'fard',
                'max_share_numerator' => 2,
                'max_share_denominator' => 3,
            ]);
        }
    }
};
