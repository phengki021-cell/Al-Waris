<?php

use App\Services\EstateCalculationService;

/**
 * Klasifikasi otomatis "jenis perhitungan" — kartu JENIS PERHITUNGAN di UI.
 * Semua klasifikasi wajib berasal dari hasil matematis mesin (method),
 * bukan pilihan pengguna.
 */
describe('Klasifikasi jenis perhitungan (Biasa / Aul / Radd / Gharrawain)', function () {
    test('amal ummi → Perhitungan Biasa, asal 4', function () {
        $dist = service()->calculate(
            ['husband' => true, 'sons' => 1, 'daughters' => 1],
            ['estate' => 1200.0]
        );

        $cls = $dist->classification;

        expect($dist->status)->toBe('valid')
            ->and($cls['code'])->toBe('biasa')
            ->and($cls['label'])->toBe('Perhitungan Biasa')
            ->and($cls['asal'])->toBe(4)
            ->and(count($cls['heirs']))->toBe(3);
    });

    test('aul → Perhitungan Aul, asal dinaikkan (13)', function () {
        $dist = service()->calculate(
            ['husband' => true, 'mother' => true, 'daughters' => 2],
            ['estate' => 13000.0]
        );

        $cls = $dist->classification;

        expect($cls['code'])->toBe('aul')
            ->and($cls['label'])->toBe("Perhitungan 'Aul")
            ->and($cls['asal'])->toBe(13);
    });

    test('radd → Perhitungan Radd', function () {
        $dist = service()->calculate(
            ['wife_count' => 1, 'mother' => true, 'daughters' => 2],
            ['estate' => 4000.0]
        );

        $cls = $dist->classification;

        expect($cls['code'])->toBe('radd')
            ->and($cls['label'])->toBe('Perhitungan Radd');
    });

    test('gharrawain istri + ibu + ayah → Perhitungan Gharrawain, asal 24', function () {
        $dist = service()->calculate(
            ['wife_count' => 1, 'mother' => true, 'father' => true],
            ['estate' => 2400.0]
        );

        $cls = $dist->classification;

        expect($cls['code'])->toBe('gharrawain')
            ->and($cls['label'])->toBe('Perhitungan Gharrawain')
            ->and($cls['asal'])->toBe(4)
            ->and($cls['reason'])->toContain('pasangan')
            ->and($dist->method)->toBe('gharawain');
    });

    test('gharrawain ibu + nenek-ibu → varian nenek pada penjelasan', function () {
        $dist = service()->calculate(
            ['mother' => true, 'maternal_grandmother' => true],
            ['estate' => 1200.0]
        );

        $cls = $dist->classification;

        expect($cls['code'])->toBe('gharrawain')
            ->and($cls['reason'])->toContain('nenek');
    });
});
