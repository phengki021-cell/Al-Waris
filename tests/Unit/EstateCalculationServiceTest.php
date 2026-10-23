<?php

use App\Domain\Faraid\EstateDistribution;
use App\Domain\Faraid\FaraidEngine;
use App\Services\EstateCalculationService;

/**
 * Kasus acuan service: nominal, validasi wasiat 1/3, pembulatan sen, konsistensi.
 * Nilai yang diharapkan dihitung manual terpisah (bukan mengulang rumus kode).
 */
describe('EstateCalculationService — validasi nominal', function () {
    test('harta kosong atau negatif → error, bukan hasil', function (array $money) {
        $dist = service()->calculate(['sons' => 1], $money);

        expect($dist->status)->toBe('error')
            ->and($dist->shares)->toBe([]);
    })->with([
        'estate kosong' => [['estate' => 0]],
        'estate negatif' => [['estate' => -1000]],
        'utang negatif' => [['estate' => 1000, 'debts' => -5]],
        'wasiat negatif' => [['estate' => 1000, 'bequest' => -1]],
    ]);

    test('wasiat melebihi 1/3 → ditolak dengan pesan batas', function () {
        $dist = service()->calculate(
            ['son' => 1, 'sons' => 1],
            ['estate' => 3000.0, 'bequest' => 1001.0]
        );

        expect($dist->status)->toBe('error')
            ->and($dist->notes[0])->toContain('sepertiga');
    });

    test('wasiat tepat 1/3 diterima; net = harta − wasiat', function () {
        $dist = service()->calculate(
            ['sons' => 1],
            ['estate' => 3000.0, 'bequest' => 1000.0]
        );

        expect($dist->status)->toBe('valid')
            ->and($dist->netEstate)->toBe(2000.0);
    });
});

describe('EstateCalculationService — konversi rupiah', function () {
    test('suami + 1 anak laki-laki: 1/4 dan 3/4 dari harta bersih (kasus acuan Rp 9 juta)', function () {
        $dist = service()->calculate(
            ['husband' => true, 'sons' => 1],
            ['estate' => 9_800_000.0, 'funeral' => 800_000.0] // bersih 9.000.000
        );

        $byCode = sharesAmounts($dist);

        expect($dist->status)->toBe('valid')
            ->and($dist->netEstate)->toBe(9_000_000.0)
            ->and($byCode['husband'])->toBe(2_250_000.0)
            ->and($byCode['son'])->toBe(6_750_000.0)
            ->and($dist->totalCents)->toBe(900_000_000); // 9.000.000 × 100
    });

    test("pembulatan sen: 'aul 3-2-8 atas Rp 1.000,01 — selisih 1 sen ke bagian terbesar", function () {
        // Bersih Rp 1000.01 = 100001 sen.
        // floor: suami 3/13 → 23077; anak (2) 8/13 → 61539; ibu 2/13 → 15384.
        // jumlah 100000 → sisa 1 sen → anak (bagian terbesar) → 61540. Total ✓.
        $dist = service()->calculate(
            ['husband' => true, 'mother' => true, 'daughters' => 2],
            ['estate' => 1000.01]
        );

        $byCode = sharesAmounts($dist);

        expect($dist->status)->toBe('valid')
            ->and($dist->totalCents)->toBe(100_001)
            ->and($byCode['husband'] * 100)->toBe(23_077.0)
            ->and($byCode['mother'] * 100)->toBe(15_384.0)
            ->and($byCode['daughter'] * 100)->toBe(61_540.0) // 61539 + selisih 1
            ->and($dist->rounding['remainder_cents'])->toBe(1)
            ->and($dist->rounding['remainder_assigned_to'])->toBe('daughter')
            ->and(array_sum(array_map(fn ($v) => $v * 100, $byCode)))->toBe(100_001.0);
    });

    test('nominal besar tetap eksak (Rp 10 miliar, radd 1:4 — ibu 1/5, anak-pr 4/5 total)', function () {
        // mother + 2 daughters → radd: ibu 1/6→1/5 (Rp 2 M), anak pr total 4/5 (Rp 8 M).
        $dist = service()->calculate(
            ['mother' => true, 'daughters' => 2],
            ['estate' => 10_000_000_000.0]
        );

        $byCode = sharesAmounts($dist);

        expect($dist->status)->toBe('valid')
            ->and($byCode['mother'])->toBe(2_000_000_000.0)
            ->and($byCode['daughter'])->toBe(8_000_000_000.0)
            ->and($dist->totalCents)->toBe(1_000_000_000_000);
    });

    test('komposisi tak didukung (kakek + cucu laki — umariyyatan) → hasil tidak dipaksakan', function () {
        $dist = service()->calculate(['grandfather' => true, 'grandsons' => 1], ['estate' => 1000.0]);

        expect($dist->status)->toBe('not_supported')
            ->and($dist->shares)->toBe([])
            ->and($dist->engineVersion)->toBe(FaraidEngine::VERSION)
            ->and($dist->notes)->not->toBe([]);
    });
});

function service(): EstateCalculationService
{
    return app(EstateCalculationService::class);
}

/** @return array<string, float> nominal rupiah per kode ahli waris (total grup) */
function sharesAmounts(EstateDistribution $dist): array
{
    $out = [];

    foreach ($dist->shares as $share) {
        $out[$share['code']] = (float) $share['total_rp'];
    }

    return $out;
}
