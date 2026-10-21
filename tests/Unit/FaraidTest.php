<?php

use App\Domain\Faraid\FaraidPreviewEngine;
use App\Domain\Faraid\RationalNumber;

describe('RationalNumber', function () {
    test('penyederhanaan pecahan', function () {
        expect(RationalNumber::of(6, 8)->simplify()->toText())->toBe('3/4');
        expect(RationalNumber::of(6, 8)->toText())->toBe('3/4'); // toText juga menyederhanakan
    });

    test('penjumlahan 1/8 + 2/8 = 3/8', function () {
        $result = RationalNumber::of(1, 8)->add(RationalNumber::of(2, 8));

        expect($result->toText())->toBe('3/8');
    });

    test('pengurangan 1 - 2/3 = 1/3', function () {
        $result = RationalNumber::of(1)->subtract(RationalNumber::of(2, 3));

        expect($result->toText())->toBe('1/3');
    });

    test('penyebut nol melempar DomainException', function () {
        RationalNumber::of(1, 0);
    })->throws(DomainException::class, 'Penyebut pecahan tidak boleh nol.');
});

describe('FaraidPreviewEngine', function () {
    test('suami + anak laki + anak perempuan', function () {
        $result = app(FaraidPreviewEngine::class)->calculate(
            husband: 1, sons: 1, daughters: 1, netEstate: 100.0
        );
        // Ratio: suami 1/4, anak laki 1/2, anak perempuan 1/4 — menjumlah 1.
        $shares = collect($result['shares']);
        $total = $shares->reduce(
            fn ($carry, $s) => $carry->add(RationalNumber::of($s['n'], $s['d'])),
            RationalNumber::of(0)
        )->simplify();

        expect($result['status'])->toBe('valid')
            ->and($total->toText())->toBe('1');
    });

    test('istri + anak laki (asabah bin-nafs)', function () {
        $result = app(FaraidPreviewEngine::class)->calculate(wife: 1, sons: 1, netEstate: 100.0);
        $shares = collect($result['shares']);

        expect($result['status'])->toBe('valid')
            ->and($shares[0]['text'])->toBe('1/8')
            ->and($shares[1]['text'])->toBe('7/8');
    });

    test('ibu saja mendapat 1/3', function () {
        $result = app(FaraidPreviewEngine::class)->calculate(mother: 1);

        expect($result['status'])->toBe('valid')
            ->and($result['shares'][0]['text'])->toBe('1/3');
    });

    test('kombinasi dengan ayah+iba+anak dosenam: belum didukung', function () {
        $result = app(FaraidPreviewEngine::class)->calculate(husband: 1, father: 1, mother: 1, daughters: 2);

        expect($result['status'])->toBe('not_supported')
            ->and($result['shares'])->toBeEmpty();
    });
});
