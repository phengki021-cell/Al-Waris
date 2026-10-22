<?php

use App\Domain\Faraid\FaraidEngine;
use App\Livewire\Calculator;
use App\Models\Calculation;

/**
 * Interaksi form → hasil → simpan ke riwayat.
 */
it('kalkulator Livewire menghitung kasus acuan (Rp 9 juta bersih)', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('funeral', '800000')
        ->set('spouse', 'husband')
        ->set('sons', 1)
        ->call('calculate');

    $result = $component->instance()->result;

    expect($result['status'])->toBe('valid')
        ->and($result['method'])->toBe('amal_ummi')
        ->and($result['net_estate'])->toBe(9_000_000.0)
        ->and(collect($result['shares'])->pluck('code')->all())->toBe(['husband', 'son'])
        ->and($result['total_cents'])->toBe(900_000_000);

    $component->assertSee('Hasil Pembagian')
        ->assertSee('2.250.000,00')
        ->assertSee('6.750.000,00')
        ->assertSee('DEMO');
});

it('hasil valid dapat disimpan ke riwayat (anonim per sesi)', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('funeral', '800000')
        ->set('spouse', 'husband')
        ->set('sons', 1)
        ->call('calculate')
        ->call('saveToHistory');

    $component->assertSee('disimpan');

    $row = Calculation::where('session_token', session()->getId())->sole();

    expect($row->reference_code)->toStartWith('ALW-')
        ->and((float) $row->net_estate)->toBe(9_000_000.0)
        ->and($row->status)->toBe('valid')
        ->and($row->method)->toBe('amal_ummi')
        ->and($row->engine_version)->toBe(FaraidEngine::VERSION)
        ->and($row->participants()->count())->toBe(2)
        ->and($row->participants->first()->results->count())->toBe(1);
});

it('kalkulator menolak kombinasi tak didukung (ayah + anak laki-laki)', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '100000')
        ->set('father', true)
        ->set('sons', 1)
        ->call('calculate')
        ->instance();

    expect($component->result['status'])->toBe('not_supported')
        ->and($component->result['shares'])->toBe([]);
});

it('kalkulator menolak wasiat > 1/3', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '3000')
        ->set('bequest', '1500') // 1/3 = 1000
        ->set('sons', 1)
        ->call('calculate')
        ->instance();

    expect($component->result['status'])->toBe('error')
        ->and($component->result['notes'][0])->toContain('sepertiga');
});

it('kalkulator mendukung ahli waris pengganti — cucu menggantikan anak yang wafat', function () {
    $testable = Livewire::test(Calculator::class)
        ->set('estate', '9000000')
        ->set('spouse', 'husband')
        ->set('grandsons', 1)
        ->call('calculate');

    $result = $testable->instance()->result;

    expect($result['status'])->toBe('valid')
        ->and(collect($result['shares'])->pluck('code')->all())->toBe(['husband', 'grandson'])
        ->and($result['total_cents'])->toBe(900_000_000);

    $testable->assertSee('Hasil Pembagian')
        ->assertSee('Cucu laki-laki');
});

it('panel hijab mahjub menampilkan pasangan pengganti yang terhalang', function () {
    $testable = Livewire::test(Calculator::class)
        ->set('estate', '1000000')
        ->set('sons', 1)
        ->set('grandsons', 1)
        ->call('calculate');

    $result = $testable->instance()->result;

    expect($result['status'])->toBe('valid')
        ->and(collect($result['shares'])->pluck('code')->all())->toBe(['son'])
        ->and(collect($result['blocked'])->pluck('code')->all())->toContain('grandson');

    $testable->assertSee('Mahjub');
});

it('kombinasi kakek + cucu ditolak aman oleh mesin (umariyyatan)', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '1000000')
        ->set('grandfather', true)
        ->set('grandsons', 1)
        ->call('calculate')
        ->instance();

    expect($component->result['status'])->toBe('not_supported')
        ->and($component->result['shares'])->toBe([])
        ->and($component->result['notes'][0])->toContain('umariyyatân');
});
