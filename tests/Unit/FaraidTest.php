<?php

use App\Domain\Faraid\FaraidEngine;
use App\Domain\Faraid\FaraidResult;
use App\Domain\Faraid\RationalNumber;

describe('RationalNumber', function () {
    test('penyederhanaan pecahan', function () {
        expect(RationalNumber::of(6, 8)->simplify()->toText())->toBe('3/4');
        expect(RationalNumber::of(6, 8)->toText())->toBe('3/4');
    });

    test('penjumlahan 1/8 + 2/8 = 3/8', function () {
        expect(RationalNumber::of(1, 8)->add(RationalNumber::of(2, 8))->toText())->toBe('3/8');
    });

    test('pengurangan 1 - 2/3 = 1/3', function () {
        expect(RationalNumber::of(1)->subtract(RationalNumber::of(2, 3))->toText())->toBe('1/3');
    });

    test('penyebut nol melempar DomainException', function () {
        RationalNumber::of(1, 0);
    })->throws(DomainException::class, 'Penyebut pecahan tidak boleh nol.');
});

describe('FaraidEngine — amal ummi', function () {
    test('suami + anak laki + anak perempuan = 1/4, 1/2, 1/4', function () {
        $r = engine([['husband', true], ['sons', 1], ['daughters', 1]]);

        expect($r->status)->toBe('valid')
            ->and($r->method)->toBe('amal_ummi')
            ->and(sharesByCode($r))->toBe([
                'husband' => '1/4',
                'son' => '1/2',
                'daughter' => '1/4',
            ]);
    });

    test('istri + 2 anak laki + 2 anak perempuan (asabah 2:1 atas sisa)', function () {
        $r = engine([['wife_count', 1], ['sons', 2], ['daughters', 2]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('amal_ummi')
            ->and($s['wife'])->toBe('1/8')
            ->and($s['son'])->toBe('7/24')      // per orang
            ->and($s['daughter'])->toBe('7/48') // per orang
            ->and(totalOf($r))->toBe('1');
    });

    test('ibu + 2 saudara laki = 1/6, masing 5/12', function () {
        $r = engine([['mother', true], ['brothers', 2]]);

        $s = sharesByCode($r);

        expect($s['mother'])->toBe('1/6')
            ->and($s['brother'])->toBe('5/12')
            ->and(totalOf($r))->toBe('1');
    });

    test('2 saudara laki + 2 saudara perempuan = asabah 2:1', function () {
        $r = engine([['brothers', 1], ['sisters', 2]]);

        $s = sharesByCode($r);

        expect($s['brother'])->toBe('1/2')
            ->and($s['sister'])->toBe('1/4')
            ->and(totalOf($r))->toBe('1');
    });

    test('ayah + ibu: ayah 2/3, ibu 1/3', function () {
        $r = engine([['father', true], ['mother', true]]);

        $s = sharesByCode($r);

        expect($s['father'])->toBe('2/3')
            ->and($s['mother'])->toBe('1/3')
            ->and(totalOf($r))->toBe('1');
    });

    test('suami + ibu + ayah: 1/2, 1/3, 1/6', function () {
        $r = engine([['husband', true], ['mother', true], ['father', true]]);

        $s = sharesByCode($r);

        expect($s['husband'])->toBe('1/2')
            ->and($s['mother'])->toBe('1/3')
            ->and($s['father'])->toBe('1/6')
            ->and(totalOf($r))->toBe('1');
    });
});

describe("FaraidEngine — 'aul", function () {
    test('suami + ibu + 2 anak perempuan: asal 12 dinaikkan jadi 13 (3/13, 2/13, 8/13)', function () {
        $r = engine([['husband', true], ['mother', true], ['daughters', 2]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('aul')
            ->and($s['husband'])->toBe('3/13')
            ->and($s['mother'])->toBe('2/13')
            ->and($s['daughter'])->toBe('4/13') // per orang
            ->and(totalOf($r))->toBe('1');
    });
});

describe('FaraidEngine — radd', function () {
    test('istri + ibu + 2 anak perempuan: istri 1/8, ibu 7/40, masing 7/20', function () {
        $r = engine([['wife_count', 1], ['mother', true], ['daughters', 2]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('radd')
            ->and($s['wife'])->toBe('1/8')
            ->and($s['mother'])->toBe('7/40')
            ->and($s['daughter'])->toBe('7/20') // per orang
            ->and(totalOf($r))->toBe('1');
    });

    test('ibu + 2 anak perempuan: ibu 1/5, masing 2/5', function () {
        $r = engine([['mother', true], ['daughters', 2]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('radd')
            ->and($s['mother'])->toBe('1/5')
            ->and($s['daughter'])->toBe('2/5')
            ->and(totalOf($r))->toBe('1');
    });

    test('ibu + 1 saudara perempuan: ibu 2/5, saudara 3/5', function () {
        $r = engine([['mother', true], ['sisters', 1]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('radd')
            ->and($s['mother'])->toBe('2/5')
            ->and($s['sister'])->toBe('3/5')
            ->and(totalOf($r))->toBe('1');
    });

    test('1 saudara perempuan sendiri: radd → mendapat seluruh harta', function () {
        $r = engine([['sisters', 1]]);

        expect($r->method)->toBe('radd')
            ->and(sharesByCode($r)['sister'])->toBe('1')
            ->and(totalOf($r))->toBe('1');
    });

    test('hanya pasangan tanpa fard lain → not_supported (radd tidak ke pasangan)', function () {
        $r = engine([['husband', true]]);

        // hanya suami: fardSum = 1/2, remainder 1/2 tidak berpasangan target
        expect($r->status)->toBe('not_supported');
    });
});

describe('FaraidEngine — gharawain & penolakan', function () {
    test('ibu + nenek-ibu (gharawain): ibu 2/3, nenek 1/3, berlabel disputed', function () {
        $r = engine([['mother', true], ['maternal_grandmother', true]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('gharawain')
            ->and($r->isDisputed())->toBeTrue()
            ->and($s['mother'])->toBe('2/3')
            ->and($s['maternal_grandmother'])->toBe('1/3')
            ->and(totalOf($r))->toBe('1');
    });

    test('suami + ayah + 1 anak laki + 2 anak perempuan → 1/4, 1/6, sisa 7/12 (2:1)', function () {
        $r = engine([['husband', true], ['father', true], ['sons', 1], ['daughters', 2]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['husband'])->toBe('1/4')
            ->and($s['father'])->toBe('1/6')
            ->and($s['son'])->toBe('7/24')          // 2 unit dari 4 unit sisa 7/12
            ->and(totalOf($r))->toBe('1');

        // Anak laki-laki TIDAK sama dengan bagian tiap anak perempuan (2 : 1).
        $perDaughter = collect($r->shares)->firstWhere('code', 'daughter');
        expect($perDaughter['per_person_text'])->toBe('7/48');
    });

    test('ayah + anak laki-laki → ayah 1/6, sisa 5/6 untuk anak (ayah tidak menyerap)', function () {
        $r = engine([['father', true], ['sons', 1]]);

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r))->toBe([
                'father' => '1/6',
                'son' => '5/6',
            ]);
    });

    test('ayah + anak laki + anak perempuan → sisa dibagi 2:1, total tetap eksak', function () {
        $r = engine([['father', true], ['sons', 1], ['daughters', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['father'])->toBe('1/6')
            ->and($s['son'])->toBe('5/9')
            ->and($s['daughter'])->toBe('5/18')
            ->and(totalOf($r))->toBe('1');
    });

    test('suami + ayah + anak laki-laki → 1/4, 1/6, 7/12', function () {
        $r = engine([['husband', true], ['father', true], ['sons', 1]]);

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r))->toBe([
                'husband' => '1/4',
                'father' => '1/6',
                'son' => '7/12',
            ]);
    });

    test('suami + istri bersamaan → error', function () {
        $r = engine([['husband', true], ['wife_count', 1]]);

        expect($r->status)->toBe('error');
    });

    test('tanpa ahli waris → error', function () {
        $r = engine([]);

        expect($r->status)->toBe('error');
    });
});

describe("FaraidEngine — tahap 3: ta'sib, hijab, & kasus acuan lain", function () {
    test("1 anak perempuan + 1 saudara perempuan: 1/2 + 1/2 (asabah bi'l-ghayr)", function () {
        $r = engine([['daughters', 1], ['sisters', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['daughter'])->toBe('1/2')
            ->and($s['sister'])->toBe('1/2')
            ->and(totalOf($r))->toBe('1');
    });

    test('2 anak perempuan + 1 saudara perempuan: 2/3 + 1/3', function () {
        $r = engine([['daughters', 2], ['sisters', 1]]);

        $s = sharesByCode($r);

        expect($s['daughter'])->toBe('1/3') // per orang (total 2/3)
            ->and($s['sister'])->toBe('1/3')
            ->and(totalOf($r))->toBe('1');
    });

    test("suami + ibu + 2 saudara perempuan: 'aul 3/8, 1/8, 1/4 per saudara", function () {
        $r = engine([['husband', true], ['mother', true], ['sisters', 2]]);

        $s = sharesByCode($r);

        expect($r->method)->toBe('aul')
            ->and($s['husband'])->toBe('3/8')
            ->and($s['mother'])->toBe('1/8')
            ->and($s['sister'])->toBe('1/4') // per orang
            ->and(totalOf($r))->toBe('1');
    });

    test('nenek-ibu sendirian: mengambil seluruh harta (radd)', function () {
        $r = engine([['maternal_grandmother', true]]);

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r)['maternal_grandmother'])->toBe('1')
            ->and(totalOf($r))->toBe('1');
    });

    test('anak laki + saudara sedarah: asabah bersama setara, saudara pr 1 satuan', function () {
        $r = engine([['sons', 1], ['brothers', 1], ['sisters', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['son'])->toBe('2/5')
            ->and($s['brother'])->toBe('2/5')
            ->and($s['sister'])->toBe('1/5')
            ->and(totalOf($r))->toBe('1')
            ->and($r->blocked)->toBe([]); // keturunan tidak menghalangi saudara
    });

    test('ayah hadir: seluruh saudara masuk daftar terhalang', function () {
        $r = engine([['father', true], ['brothers', 2], ['sisters', 1]]);

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r)['father'])->toBe('1')
            ->and(collect($r->blocked)->pluck('code')->all())->toBe(['brother', 'sister']);
    });

    test('ayah hadir: nenek-ibu terhalang & tidak menerima bagian', function () {
        $r = engine([['father', true], ['maternal_grandmother', true]]);

        expect($r->status)->toBe('valid')
            ->and(collect($r->blocked)->pluck('code'))->toContain('maternal_grandmother')
            ->and(collect($r->shares)->pluck('code'))->not->toContain('maternal_grandmother')
            ->and(sharesByCode($r)['father'])->toBe('1') // asabah satu-satunya
            ->and(totalOf($r))->toBe('1');
    });

    test('salah satu kasus klasik: ibu + ayah + suami (ayah menangkap sisa)', function () {
        // Kasus klasik "1/2 + 1/3 + 1/6": ayah absah ketika tidak ada keturunan.
        $r = engine([['husband', true], ['father', true], ['mother', true]]);

        $s = sharesByCode($r);

        expect($s['husband'])->toBe('1/2')
            ->and($s['mother'])->toBe('1/3')
            ->and($s['father'])->toBe('1/6')
            ->and(totalOf($r))->toBe('1');
    });

    test('input negatif dilempar error terstruktur', function () {
        $r = engine([['sons', -1]]);

        expect($r->status)->toBe('error');
    });

    test('input desimal (0.5 orang) ditolak', function () {
        $r = engine([['sons', 1.5]]);

        expect($r->status)->toBe('error');
    });

    test('suami sendirian — sisa tak bisa diradd ke pasangan → not_supported', function () {
        $r = engine([['husband', true]]);

        expect($r->status)->toBe('not_supported');
    });

    test('istri sendiri tanpa fard lain → not_supported (dzawil arham)', function () {
        $r = engine([['wife_count', 1]]);

        expect($r->status)->toBe('not_supported');
    });
});

// ---------------------------------------------------------------------------

describe('FaraidEngine — ahli waris pengganti (v0.4: kakek & cucu dari anak laki)', function () {
    test('kakek + ibu: kakek 2/3, ibu 1/3 (analog ayah yang menangkap sisa)', function () {
        $r = engine([['grandfather', true], ['mother', true]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['mother'])->toBe('1/3')
            ->and($s['grandfather'])->toBe('2/3')
            ->and(totalOf($r))->toBe('1');
    });

    test('kakek + 1 anak perempuan: kakek 1/2, anak pr 1/2', function () {
        $r = engine([['grandfather', true], ['daughters', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['daughter'])->toBe('1/2')
            ->and($s['grandfather'])->toBe('1/2')
            ->and(totalOf($r))->toBe('1');
    });

    test('suami + cucu laki (dari anak laki yang wafat): suami 1/4 — cucu adalah keturunan', function () {
        $r = engine([['husband', true], ['grandsons', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['husband'])->toBe('1/4')
            ->and($s['grandson'])->toBe('3/4')
            ->and(totalOf($r))->toBe('1');
    });

    test('2 anak pr + 1 cucu laki: 2/3 (1/3 per orang) + sisa 1/3 untuk cucu', function () {
        $r = engine([['daughters', 2], ['grandsons', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['daughter'])->toBe('1/3') // per orang
            ->and($s['grandson'])->toBe('1/3')
            ->and(totalOf($r))->toBe('1');
    });

    test('cucu laki + cucu pr: asabah pengganti 2:1 (2/3, 1/3)', function () {
        $r = engine([['grandsons', 1], ['granddaughters', 1]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['grandson'])->toBe('2/3')
            ->and($s['granddaughter'])->toBe('1/3')
            ->and(totalOf($r))->toBe('1');
    });

    test('cucu pr sendirian: fard 1/2 → radd → seluruh harta', function () {
        $r = engine([['granddaughters', 1]]);

        expect($r->method)->toBe('radd')
            ->and(sharesByCode($r)['granddaughter'])->toBe('1')
            ->and(totalOf($r))->toBe('1');
    });

    test('2 cucu pr: fard 2/3 → radd → seluruh harta (per orang 1/2)', function () {
        $r = engine([['granddaughters', 2]]);

        expect($r->method)->toBe('radd')
            ->and(sharesByCode($r)['granddaughter'])->toBe('1/2')
            ->and(totalOf($r))->toBe('1');
    });

    test('anak pr + cucu pr: cucu pr terhalang anak pr, sisa diradd ke anak pr', function () {
        $r = engine([['daughters', 1], ['granddaughters', 1]]);

        $blockedCodes = collect($r->blocked)->pluck('code')->all();

        expect($r->status)->toBe('valid')
            ->and(collect($r->shares)->pluck('code')->all())->toBe(['daughter'])
            ->and(sharesByCode($r)['daughter'])->toBe('1')
            ->and($blockedCodes)->toContain('granddaughter')
            ->and(totalOf($r))->toBe('1');
    });

    test('anak laki + cucu laki: cucu terhalang oleh anak langsung', function () {
        $r = engine([['sons', 1], ['grandsons', 1]]);

        $blockedCodes = collect($r->blocked)->pluck('code')->all();

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r)['son'])->toBe('1')
            ->and($blockedCodes)->toContain('grandson')
            ->and(totalOf($r))->toBe('1');
    });

    test('kakek + ayah: kakek terhalang, ayah menangkap seluruh sisa', function () {
        $r = engine([['father', true], ['grandfather', true]]);

        $blockedCodes = collect($r->blocked)->pluck('code')->all();

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r)['father'])->toBe('1')
            ->and($blockedCodes)->toContain('grandfather')
            ->and(totalOf($r))->toBe('1');
    });

    test('kakek + anak laki: kakek terhalang oleh anak langsung', function () {
        $r = engine([['grandfather', true], ['sons', 1]]);

        $blockedCodes = collect($r->blocked)->pluck('code')->all();

        expect($r->status)->toBe('valid')
            ->and(sharesByCode($r)['son'])->toBe('1')
            ->and($blockedCodes)->toContain('grandfather')
            ->and(totalOf($r))->toBe('1');
    });

    test('cucu laki + saudara pr (tanpa saudara laki): cucu 1, saudara pr terhalang', function () {
        $r = engine([['grandsons', 1], ['sisters', 1]]);

        $blockedCodes = collect($r->blocked)->pluck('code')->all();

        expect($r->status)->toBe('valid')
            ->and(collect($r->shares)->pluck('code')->all())->toBe(['grandson'])
            ->and(sharesByCode($r)['grandson'])->toBe('1')
            ->and($blockedCodes)->toContain('sister')
            ->and(totalOf($r))->toBe('1');
    });

    test('kakek + saudara sedarah → not_supported (umariyyatân)', function () {
        $r = engine([['grandfather', true], ['brothers', 1]]);

        expect($r->status)->toBe('not_supported');
    });

    test('kakek + cucu → not_supported (umariyyatân)', function () {
        $r = engine([['grandfather', true], ['grandsons', 1]]);

        expect($r->status)->toBe('not_supported');
    });

    test('kakek + nenek-ibu (tanpa ibu): nenek 1/6, kakek menyerap sisa 5/6', function () {
        // fard: nenek-ibu 1/6; sisa 5/6 → kakek (asabah menyerap sisa).
        $r = engine([['grandfather', true], ['maternal_grandmother', true]]);

        $s = sharesByCode($r);

        expect($r->status)->toBe('valid')
            ->and($s['maternal_grandmother'])->toBe('1/6')
            ->and($s['grandfather'])->toBe('5/6')
            ->and(totalOf($r))->toBe('1');
    });
});

function engine(array $pairs): FaraidResult
{
    $input = [];

    foreach ($pairs as [$key, $value]) {
        $input[$key] = $value;
    }

    return app(FaraidEngine::class)->calculate($input);
}

/** @return array<string, string> teks pecahan per orang bila berjumlah banyak */
function sharesByCode(FaraidResult $r): array
{
    $out = [];

    foreach ($r->shares as $share) {
        $out[$share['code']] = $share['per_person_text'] ?? $share['text'];
    }

    return $out;
}

/** Jumlah seluruh bagian — pecahan pada shares adalah TOTAL GRUP. */
function totalOf(FaraidResult $r): string
{
    $sum = RationalNumber::of(0);

    foreach ($r->shares as $share) {
        $sum = $sum->add(RationalNumber::of($share['n'], $share['d']));
    }

    return $sum->simplify()->toText();
}
