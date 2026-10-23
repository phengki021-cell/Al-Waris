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

    $testable->assertSee('Hijab Hirman')
        ->assertSee('Terhalang oleh anak laki-laki');
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

it('kartu AYAH dan IBU dapat dipilih lalu dibatalkan lewat toggle()', function () {
    $component = Livewire::test(Calculator::class);

    // Pilih Ayah dan Ibu bersamaan, keduanya tersimpan.
    $component->call('toggle', 'father')->call('toggle', 'mother');
    expect($component->instance()->father)->toBeTrue()
        ->and($component->instance()->mother)->toBeTrue();

    // Batal memilih Ibu — Ayah tidak ikut hilang (input lain dipertahankan).
    $component->call('toggle', 'mother');
    expect($component->instance()->father)->toBeTrue()
        ->and($component->instance()->mother)->toBeFalse();

    // Pilihan ikut memengaruhi hasil perhitungan.
    $component->set('estate', '12000000')->call('calculate')->instance();
    expect($component->result['shares'])->not->toBeEmpty();
});

it('toggle() menolak properti di luar whitelist', function () {
    $component = Livewire::test(Calculator::class)->set('estate', '5000');

    $component->call('toggle', 'estate');
    expect($component->get('estate'))->toBe('5000');

    $component->call('toggle', 'sons');
    expect($component->instance()->sons)->toBe(0);
});

it('toggle() menggeser pasangan pada mode perdata (KUHPerdata)', function () {
    $component = Livewire::test(Calculator::class);

    $component->call('toggle', 'civilSpouse');
    expect($component->instance()->civilSpouse)->toBeTrue();

    $component->call('toggle', 'civilSpouse');
    expect($component->instance()->civilSpouse)->toBeFalse();
});

it('tombol reset memunculkan dialog konfirmasi, bukan langsung menghapus', function () {
    $component = Livewire::test(Calculator::class);

    $component->assertSee('Apakah kamu yakin ingin menghapus semua data')
        ->assertSee('Batal')
        ->assertSee('Ya, Mulai Baru');
});

it('dua istri berbagi kolektif 1/8 dan tampil sebagai per istri', function () {
    $testable = Livewire::test(Calculator::class)
        ->set('estate', '120000000')
        ->set('spouse', 'wife')
        ->set('wifeCount', 2)
        ->set('sons', 1)
        ->call('calculate');

    $testable->assertSee('1/16')->assertSee('7.500.000')->assertSee('kolektif');

    $component = $testable->instance();

    $shares = collect($component->result['shares']);
    $wife = $shares->firstWhere('code', 'wife');
    $son = $shares->firstWhere('code', 'son');

    expect($wife['text'])->toBe('1/8')
        ->and($wife['per_person_text'])->toBe('1/16')
        ->and((float) $wife['total_rp'])->toBe(15_000_000.0)
        ->and((float) $wife['per_person_rp'])->toBe(7_500_000.0)
        ->and((float) $son['total_rp'])->toBe(105_000_000.0);
});

it('papan keterangan ahli waris ter-update langsung saat pilihan berubah', function () {
    $component = Livewire::test(Calculator::class);

    // Awal: semua idle + petunjuk memilih.
    $entries = collect($component->get('heirEntries'))->keyBy('code');
    expect($entries['mother']['status'])->toBe('idle')
        ->and($entries['sons']['status'])->toBe('idle');

    // Pilih IBU → baris ibu langsung berubah statusnya, tanpa memanggil calculate().
    $component->set('mother', true);
    $entries = collect($component->get('heirEntries'))->keyBy('code');
    expect($entries['mother']['status'])->toBe('fard')
        ->and($entries['mother']['reason'])->not->toBeEmpty();

    // Tambah anak laki-laki → ibu bergeser ke 1/6 dan anak laki-laki jadi asabah.
    $component->set('sons', 2);
    $entries = collect($component->get('heirEntries'))->keyBy('code');
    expect($entries['mother']['share_text'])->toBe('1/6')
        ->and($entries['sons']['status'])->toBe('asabah');

    // Kalkulate tetap konsisten dengan papan: hasil valid, ibu 1/6.
    $component->set('estate', '120000000')->call('calculate')->instance();
    $shares = collect($component->result['shares']);
    expect($component->result['status'])->toBe('valid')
        ->and($shares->firstWhere('code', 'mother')['text'])->toBe('1/6');
});

it('toggle AYAH juga memperbarui papan keterangan', function () {
    $component = Livewire::test(Calculator::class);

    $component->call('toggle', 'father');
    $entries = collect($component->get('heirEntries'))->keyBy('code');
    expect($entries['father']['status'])->toBe('asabah')
        ->and($entries['father']['reason'])->toContain('asabah');

    $component->call('toggle', 'father');
    $entries = collect($component->get('heirEntries'))->keyBy('code');
    expect($entries['father']['status'])->toBe('idle');
});
