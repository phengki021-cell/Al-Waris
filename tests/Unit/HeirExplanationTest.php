<?php

use App\Services\HeirExplanationService;

/**
 * Papan keterangan dinamis: status, bagian, dan alasan per ahli waris —
 * seluruh pecahan takaran diambil dari output mesin (tidak dihitung di sini).
 */
it('papan keterangan: suami + ayah + ibu (tanpa anak) — ibu 1/3, ayah sisa', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => true, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
            'father' => true, 'mother' => true, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 60_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('valid')
        ->and($panel['has_money'])->toBeTrue();

    $entries = collect($panel['entries'])->keyBy('code');

    $husband = $entries['husband'];
    expect($husband['status'])->toBe('fard')
        ->and($husband['label'])->toBe('Berhak menerima')
        ->and($husband['share_text'])->toBe('1/2')
        ->and($husband['reason'])->toContain('1/2');

    $father = $entries['father'];
    expect($father['status'])->toBe('asabah')
        // 1/6 fard — total bab tiga pihak = 1/2 + 1/6 + 1/3 = 1 (tidak ada radd)
        ->and($father['share_text'])->toBe('1/6')
        ->and($father['reason'])->toContain('asabah');

    $mother = $entries['mother'];
    expect($mother['status'])->toBe('fard')
        ->and($mother['share_text'])->toBe('1/3')
        ->and($mother['reason'])->toContain('1/3');
});

it('papan keterangan: istri + ayah + ibu + anak laki-laki (ayah + laki-laki aman ditolak)', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 1, 'sons' => 1, 'daughters' => 0,
            'father' => true, 'mother' => true, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 120_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('not_supported')
        ->and($panel['note'])->not->toBeNull();

    $entries = collect($panel['entries'])->keyBy('code');
    // Ayah & anak laki-laki dipilih → status pending, TANPA angka menyesatkan.
    foreach (['father', 'sons'] as $code) {
        expect($entries[$code]['status'])->toBe('pending')
            ->and($entries[$code]['share_text'])->toBeNull()
            ->and($entries[$code]['amount_text'])->toBeNull();
    }
});

it('papan keterangan: istri + ayah + ibu + anak perempuan', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 1, 'sons' => 0, 'daughters' => 1,
            'father' => true, 'mother' => true, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 120_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('valid');

    $entries = collect($panel['entries'])->keyBy('code');
    expect($entries['wife']['share_text'])->toBe('1/8')
        ->and($entries['wife']['reason'])->toContain('kolektif');
    // 1/8 + 1/2 + 1/6 = 19/24 → sisa 5/24 via asabah pada ayah (sesuai mesin).
    expect($entries['father']['status'])->toBe('asabah')
        ->and($entries['father']['share_text'])->toBe('5/24')
        ->and($entries['mother']['status'])->toBe('fard')
        ->and($entries['mother']['share_text'])->toBe('1/6')
        ->and($entries['daughters']['status'])->toBe('fard')
        ->and($entries['daughters']['share_text'])->toBe('1/2');
});

it('papan keterangan: ayah + ibu tanpa anak (sisa ke ayah)', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 0, 'sons' => 0, 'daughters' => 0,
            'father' => true, 'mother' => true, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 90_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('valid');

    $entries = collect($panel['entries'])->keyBy('code');
    expect($entries['mother']['share_text'])->toBe('1/3')
        ->and($entries['mother']['reason'])->toContain('1/3')
        ->and($entries['father']['status'])->toBe('asabah')
        ->and($entries['father']['share_text'])->toBe('2/3');
});

it('papan keterangan: anak laki-laki dan anak perempuan bersama-sama (2:1)', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 0, 'sons' => 2, 'daughters' => 1,
            'father' => false, 'mother' => false, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 100_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('valid');

    $entries = collect($panel['entries'])->keyBy('code');
    // Sisa 100% : unit laki-laki 2, perempuan 1 → 4/5 : 1/5 (rasio 2 : 1).
    expect($entries['sons']['status'])->toBe('asabah')
        ->and($entries['sons']['share_text'])->toBe('4/5')
        ->and($entries['sons']['per_person_share'])->toBe('2/5')
        ->and($entries['sons']['amount_text'])->toContain('80.000.000')
        ->and($entries['daughters']['status'])->toBe('asabah')
        ->and($entries['daughters']['share_text'])->toBe('1/5')
        ->and($entries['daughters']['reason'])->toContain('1 : 2');
});

it('papan keterangan: cucu laki-laki terhalang anak laki-laki dengan alasan', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 0, 'sons' => 1, 'daughters' => 0,
            'father' => false, 'mother' => false, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 1, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 100_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['status'])->toBe('valid');

    $entries = collect($panel['entries'])->keyBy('code');
    expect($entries['grandsons']['status'])->toBe('mahjub')
        ->and($entries['grandsons']['label'])->toBe('Terhalang (mahjub)')
        ->and($entries['grandsons']['reason'])->not->toBeEmpty()
        ->and($entries['grandsons']['share_text'])->toBeNull();
});

it('papan keterangan: ahli waris belum dipilih tetap tercantum dengan status idle', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 2, 'sons' => 0, 'daughters' => 0,
            'father' => false, 'mother' => false, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 120_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    $entries = collect($panel['entries'])->keyBy('code');

    // Isteri dipilih → entri istri pandangan mesin (0 encoder)
    expect($entries['wife']['status'])->toBe('pending')
        ->and($entries['father']['status'])->toBe('idle')
        ->and($entries['father']['reason'])->toContain('Pilih AYAH')
        ->and($entries['mother']['status'])->toBe('idle');
});

it('papan keterangan tanpa nilai harta: pecahan tayang, nominal disembunyikan', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 1, 'sons' => 1, 'daughters' => 0,
            'father' => false, 'mother' => false, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 0, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    expect($panel['has_money'])->toBeFalse();

    $wife = collect($panel['entries'])->firstWhere('code', 'wife');
    expect($wife['share_text'])->toBe('1/8')
        ->and($wife['amount_text'])->toBeNull();
});

it('papan keterangan: dua istri dibagi kolektif dan per orang ditampilkan', function () {
    $panel = app(HeirExplanationService::class)->explain(
        heirs: ['husband' => false, 'wife_count' => 2, 'sons' => 1, 'daughters' => 0,
            'father' => false, 'mother' => false, 'maternal_grandmother' => false,
            'grandfather' => false, 'grandsons' => 0, 'granddaughters' => 0,
            'brothers' => 0, 'sisters' => 0],
        money: ['estate' => 120_000_000, 'debts' => 0, 'bequest' => 0, 'funeral' => 0],
    );

    $wife = collect($panel['entries'])->firstWhere('code', 'wife');
    expect($wife['share_text'])->toBe('1/8')
        ->and($wife['per_person_share'])->toBe('1/16')
        ->and($wife['amount_text'])->toBe('Rp 15.000.000,00')
        ->and($wife['per_person_amount_text'])->toBe('Rp 7.500.000,00')
        ->and($wife['reason'])->toContain('kolektif seluruh istri');
});
