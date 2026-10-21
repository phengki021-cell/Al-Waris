<?php

namespace Database\Seeders;

use App\Models\Calculation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoCalculationSeeder extends Seeder
{
    /**
     * 2 riwayat demonstrasi berlabel DEMO agar halaman Riwayat tidak kosong
     * pada pengembangan. Tidak untuk data produksi.
     */
    public function run(): void
    {
        if (Calculation::count() > 0) {
            return; // idempotent: jangan duplikasi pada re-seed partial
        }

        $heirsByCode = DB::table('heirs')->pluck('id', 'code');

        $demo = [
            [
                'reference_code' => 'WRS-2026-000001',
                'estate_total' => 240_000_000,
                'debts_total' => 20_000_000,
                'bequests_total' => 5_000_000,
                'funeral_expense' => 5_000_000,
                'net_estate' => 210_000_000,
                'method' => 'amal_ummi',
                'status' => 'valid',
                'notes' => 'DEMO: Suami, ibu, 2 anak perempuan.',
                'participants' => [
                    ['code' => 'husband', 'name_id' => 'Suami', 'count' => 1, 'share_n' => 1, 'share_d' => 4],
                    ['code' => 'mother', 'name_id' => 'Ibu', 'count' => 1, 'share_n' => 1, 'share_d' => 6],
                    ['code' => 'daughter', 'name_id' => 'Anak perempuan', 'count' => 2, 'share_n' => 5, 'share_d' => 12],
                ],
            ],
            [
                'reference_code' => 'WRS-2026-000002',
                'estate_total' => 100_000_000,
                'debts_total' => 0,
                'bequests_total' => 0,
                'funeral_expense' => 0,
                'net_estate' => 100_000_000,
                'method' => 'amal_ummi',
                'status' => 'valid',
                'notes' => 'DEMO: Istirahat tanpa keturunan — istri 1/6.',
                'participants' => [
                    ['code' => 'wife', 'name_id' => 'Istri', 'count' => 1, 'share_n' => 1, 'share_d' => 8],
                    ['code' => 'son', 'name_id' => 'Anak laki-laki', 'count' => 2, 'share_n' => 7, 'share_d' => 16],
                    ['code' => 'daughter', 'name_id' => 'Anak perempuan', 'count' => 2, 'share_n' => 7, 'share_d' => 32],
                ],
            ],
        ];

        foreach ($demo as $item) {
            $calculation = Calculation::create([
                'user_id' => null,
                'session_token' => null,
                'reference_code' => $item['reference_code'],
                'estate_total' => $item['estate_total'],
                'debts_total' => $item['debts_total'],
                'bequests_total' => $item['bequests_total'],
                'funeral_expense' => $item['funeral_expense'],
                'net_estate' => $item['net_estate'],
                'method' => $item['method'],
                'status' => $item['status'],
                'notes' => $item['notes'],
            ]);

            foreach ($item['participants'] as $i => $p) {
                $participant = $calculation->participants()->create([
                    'heir_id' => $heirsByCode[$p['code']] ?? null,
                    'code' => $p['code'],
                    'name_id' => $p['name_id'],
                    'count' => $p['count'],
                    'sort_order' => $i,
                ]);

                $participant->results()->create([
                    'calculation_id' => $calculation->id,
                    'numerator' => $p['share_n'],
                    'denominator' => $p['share_d'],
                    'share_text' => $p['share_n'].'/'.$p['share_d'],
                    'amount' => round($item['net_estate'] * $p['share_n'] / $p['share_d'], 2),
                    'rounding_method' => 'none',
                    'note' => null,
                ]);
            }

            $q411 = DB::table('sources')->where('code', 'QURAN-4-11')->value('id');
            $q412 = DB::table('sources')->where('code', 'QURAN-4-12')->value('id');
            foreach (array_filter([$q411, $q412]) as $sourceId) {
                DB::table('calculation_source')->insert([
                    'calculation_id' => $calculation->id,
                    'source_id' => $sourceId,
                    'rule_code' => 'amal-ummi',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
