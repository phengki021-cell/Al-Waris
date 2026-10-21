<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShareSeeder extends Seeder
{
    /**
     * Aturan bagian pasti (dzawil furudh) versi dasar — SEMUA berlabel DEMO.
     * Rinciannya akan divalidasi bersama ahli faraid sebelum dibuka ke publik.
     */
    public function run(): void
    {
        $source = fn (string $code) => DB::table('sources')->where('code', $code)->value('id');

        $q411 = $source('QURAN-4-11');
        $q412 = $source('QURAN-4-12');

        $rows = [
            // [heir_code, n, d, condition, source_id]
            ['husband', 1, 2, 'Tanpa keturunan almarhum', $q412],
            ['husband', 1, 4, 'Ada keturunan almarhum (anak/anak anak)', $q412],
            ['wife', 1, 4, 'Tanpa keturunan almarhum', $q412],
            ['wife', 1, 8, 'Ada keturunan almarhum', $q412],
            ['daughter', 1, 2, 'Hanya satu anak perempuan tanpa anak laki-laki', $q411],
            ['daughter', 2, 3, 'Dua anak perempuan atau lebih (tanpa anak laki-laki), dibagi rata', $q411],
            ['father', 1, 6, 'Tanpa anak laki-laki almarhum', $q411],
            ['mother', 1, 6, 'Ada keturunan atau saudara almarhum', $q411],
            ['mother', 1, 3, 'Tanpa keturunan dan tanpa saudara', $q411],
            ['grandmother_mother', 1, 6, 'Bila ibu tidak ada (keadaan tertentu)', $q411],
            ['grandfather_father', 1, 6, 'Bila ayah tidak ada (keadaan tertentu)', $q411],
        ];

        $heirIds = DB::table('heirs')->pluck('id', 'code');

        foreach ($rows as [$code, $n, $d, $condition, $sourceId]) {
            DB::table('shares')->updateOrInsert(
                [
                    'heir_id' => $heirIds[$code] ?? null,
                    'numerator' => $n,
                    'denominator' => $d,
                    'condition_hash' => md5($condition),
                ],
                [
                    'heir_id' => $heirIds[$code],
                    'numerator' => $n,
                    'denominator' => $d,
                    'condition' => $condition,
                    'condition_hash' => md5($condition),
                    'note' => 'DATA DEMO — verifikasi ahli faraid.',
                    'source_id' => $sourceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
