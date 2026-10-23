<?php

use App\Services\HeirExplanationService;

/**
 * Panel terpisah Jenis 'Ashabah (bin nafsi / bil ghair / ma'al ghair)
 * dan Hijab (hirman / nuqshan) — semuanya mengikuti output mesin faraid.
 */
function panel(array $heirs, float $estate = 100_000_000.0): array
{
    return app(HeirExplanationService::class)->explain(
        heirs: $heirs,
        money: ['estate' => $estate, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );
}

it("'ashabah: kartu 'ashabah muncul di bagian tersendiri dengan jenisnya", function () {
    $p = panel(['husband' => false, 'wife_count' => 0, 'sons' => 2, 'daughters' => 1,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);

    $entries = collect($p['entries'])->keyBy('code');

    expect($entries['sons']['status'])->toBe('asabah')
        ->and($entries['sons']['asabah_type'])->toBe('bin_nafsi')
        ->and($entries['sons']['asabah_type_label'])->toBe("'Ashabah bin Nafsi")
        ->and($entries['sons']['asabah_way'])->toContain('Sisa harta')
        ->and($entries['sons']['hijab'])->toBeNull()
        // bil ghair: anak perempuan bersama anak laki-laki, rasio 2 : 1
        ->and($entries['daughters']['status'])->toBe('asabah')
        ->and($entries['daughters']['asabah_type'])->toBe('bil_ghair')
        ->and($entries['daughters']['asabah_type_label'])->toBe("'Ashabah bil Ghair")
        ->and($entries['daughters']['reason'])->toContain('2 : 1');
});

it("'ashabah: saudara perempuan ma'al ghair membagi sisa bersama anak perempuan", function () {
    $p = panel(['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 1,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 1]);

    $entries = collect($p['entries'])->keyBy('code');
    expect($entries['sisters']['status'])->toBe('asabah')
        ->and($entries['sisters']['asabah_type'])->toBe('ma_al_ghair')
        ->and($entries['sisters']['asabah_type_label'])->toBe("'Ashabah ma'al Ghair")
        // 1/2 fard anak perempuan + 1/2 sisa ma'al ghair
        ->and($entries['sisters']['share_text'])->toBe('1/2');
});

it('hijab hirman: saudara laki-laki terhalang ayah (penyebab + kaidah tampil)', function () {
    $p = panel(['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
        'father' => true, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 1, 'sisters' => 0]);

    $entries = collect($p['entries'])->keyBy('code');
    $brother = $entries['brothers'];

    expect($brother['status'])->toBe('mahjub')
        ->and($brother['hijab']['type'])->toBe('hirman')
        ->and($brother['hijab']['type_label'])->toBe('Hijab Hirman')
        ->and($brother['hijab']['cause'])->toBe('Ayah')
        ->and($brother['hijab']['reason'])->toContain('ayah')
        ->and($brother['share_text'])->toBeNull()
        ->and($brother['amount_text'])->toBeNull();
});

it('hijab hirman: kakek terhalang ayah; cucu terhalang anak laki-laki', function () {
    $p1 = panel(['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
        'father' => true, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => true, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    $kakek = collect($p1['entries'])->keyBy('code')['grandfather'];

    expect($kakek['hijab']['type'])->toBe('hirman')
        ->and($kakek['hijab']['cause'])->toBe('Ayah');

    $p2 = panel(['husband' => false, 'wife_count' => 0, 'sons' => 1, 'daughters' => 0,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 1, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    $cucu = collect($p2['entries'])->keyBy('code')['grandsons'];

    expect($cucu['hijab']['type'])->toBe('hirman')
        ->and($cucu['hijab']['cause'])->toBe('Anak laki-laki');
});

it('hijab nuqshan: suami berkurang 1/2 menjadi 1/4 karena keturunan', function () {
    $p = panel(['husband' => true, 'wife_count' => 0, 'sons' => 0, 'daughters' => 1,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);

    $husband = collect($p['entries'])->keyBy('code')['husband'];
    expect($husband['status'])->toBe('fard')
        ->and($husband['share_text'])->toBe('1/4')
        ->and($husband['hijab']['type'])->toBe('nuqshan')
        ->and($husband['hijab']['type_label'])->toBe('Hijab Nuqshan')
        ->and($husband['hijab']['cause'])->toContain('Keturunan')
        ->and($husband['hijab']['reason'])->toContain('1/2 menjadi 1/4')
        ->and($husband['hijab']['final_share'])->toBe('1/4')
        ->and($husband['amount_text'])->toContain('25.000.000');
});

it('hijab nuqshan: istri 1/4 menjadi 1/8; ibu 1/3 menjadi 1/6; tanpa kondisi tetap 1/3', function () {
    $p1 = panel(['husband' => false, 'wife_count' => 1, 'sons' => 1, 'daughters' => 0,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    $istri = collect($p1['entries'])->keyBy('code')['wife'];
    expect($istri['hijab']['type'])->toBe('nuqshan')
        ->and($istri['hijab']['reason'])->toContain('1/4 menjadi 1/8')
        ->and($istri['hijab']['final_share'])->toBe('1/8');

    $p2 = panel(['husband' => false, 'wife_count' => 0, 'sons' => 2, 'daughters' => 0,
        'father' => false, 'mother' => true, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    $ibu = collect($p2['entries'])->keyBy('code')['mother'];
    expect($ibu['hijab']['type'])->toBe('nuqshan')
        ->and($ibu['hijab']['reason'])->toContain('1/3 menjadi 1/6')
        ->and($ibu['share_text'])->toBe('1/6');

    // Ibu TANPA keturunan & saudara < 2 → bukan nuqshan, tetap 1/3
    // (kasus ibu + 1 saudara laki-laki: saudara menjadi 'ashabah sisa).
    $p3 = panel(['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
        'father' => false, 'mother' => true, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 1, 'sisters' => 0]);
    $entries3 = collect($p3['entries'])->keyBy('code');
    expect($entries3['mother']['share_text'])->toBe('1/3')
        ->and($entries3['mother']['hijab'])->toBeNull();
});

it('hijab nuqshan ibu juga dipicu dua saudara atau lebih (sesuai mesin)', function () {
    $p = panel(['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
        'father' => false, 'mother' => true, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 2, 'sisters' => 0]);

    $ibu = collect($p['entries'])->keyBy('code')['mother'];
    expect($ibu['share_text'])->toBe('1/6')
        ->and($ibu['hijab']['type'])->toBe('nuqshan')
        ->and($ibu['hijab']['cause'])->toContain('Dua saudara');
});

it('total pembagian tetap utuh pada kasus asabah & nuqshan', function () {
    // 2 anak L + 1 anak P harta 100 juta → asabah menyerap seluruh sisa.
    $p = panel(['husband' => false, 'wife_count' => 0, 'sons' => 2, 'daughters' => 1,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    expect($p['status'])->toBe('valid');

    $liv = Livewire::test(App\Livewire\Calculator::class)
        ->set('estate', '100000000')
        ->set('spouse', 'husband')
        ->set('sons', 2)
        ->set('daughters', 1)
        ->call('calculate');

    expect($liv->get('result')['status'])->toBe('valid')
        ->and($liv->get('result')['total_cents'])->toBe(10_000_000_000);

    // Suami + anak perempuan: 1/4 pasangan + 1/2 fard (diradd) = 100%
    // — nuqshan pasangan tampil, tanpa ahli waris kehilangan hak seluruhnya.
    $q = panel(['husband' => true, 'wife_count' => 0, 'sons' => 0, 'daughters' => 1,
        'father' => false, 'mother' => false, 'maternal_grandmother' => false,
        'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0, 'brothers' => 0, 'sisters' => 0]);
    $entriesQ = collect($q['entries'])->keyBy('code');

    expect($q['status'])->toBe('valid')
        ->and($entriesQ['husband']['hijab']['type'])->toBe('nuqshan')
        ->and($entriesQ['husband']['amount_text'])->toContain('25.000.000')
        ->and($entriesQ['daughters']['status'])->toBe('fard')
        ->and($entriesQ['daughters']['amount_text'])->toContain('75.000.000');
});
