<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Pelengkapan penjelasan anak perempuan:
 * kedudukannya dzawil furudh (sendirian 1/2, dua-lebih 2/3),
 * namun menjadi ashabah bil ghair bila bersama anak laki-laki (nisbah 2:1).
 */
return new class extends Migration
{
    private const DETAIL = 'Dzawil furudh: sendirian 1/2; dua atau lebih 2/3 dibagi sama. Bila bersama anak laki-laki, ia menjadi ashabah bil ghair — bagian pastinya gugur dan dibagi nisbah 2 : 1 (laki-laki : perempuan).';

    public function up(): void
    {
        if (Schema::hasTable('heirs')) {
            DB::table('heirs')->where('code', 'daughter')->update(['detail' => self::DETAIL]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('heirs')) {
            DB::table('heirs')->where('code', 'daughter')->update([
                'detail' => 'Sendirian 1/2; dua atau lebih 2/3 dibagi sama.',
            ]);
        }
    }
};
