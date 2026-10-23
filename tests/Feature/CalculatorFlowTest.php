<?php

use App\Domain\Faraid\FaraidEngine;
use App\Livewire\Calculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Tahap 4 — alur pengguna kalkulator:
 * input → ringkasan → hasil → kembali mengubah input / mulai perhitungan baru.
 * Kesalahan yang dapat dipulihkan tidak boleh mengosongkan input pengguna.
 */
it('alur: hitung → hasil → kembali mengubah input (input tetap) → hasil tersembunyi', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('decedentGender', 'perempuan')
        ->set('spouse', 'husband')
        ->set('sons', 1)
        ->call('calculate');

    expect($component->instance()->showResult)->toBeTrue()
        ->and($component->instance()->result['status'])->toBe('valid');

    $component
        ->assertSee('Kembali mengubah input')
        ->assertSee('Mulai perhitungan baru')
        ->call('backToInput');

    expect($component->instance()->showResult)->toBeFalse()
        ->and($component->instance()->estate)->toBe('9800000') // input tidak hilang
        ->and($component->instance()->spouse)->toBe('husband');
});

it('alur: mulai perhitungan baru mengosongkan seluruh input dan hasil', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('sons', 2)
        ->call('calculate')
        ->call('startNew');

    expect($component->instance()->estate)->toBe('')
        ->and($component->instance()->sons)->toBe(0)
        ->and($component->instance()->result)->toBeNull()
        ->and($component->instance()->showResult)->toBeFalse();
});

it('kesalahan validasi memunculkan pesan spesifik tanpa menghapus input', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '')        // wajib diisi
        ->set('debts', '-5')       // negatif
        ->set('spouse', 'husband')
        ->call('calculate');

    $component
        ->assertHasErrors(['estate', 'debts'])
        // Pesan spesifik per field dari MESSAGES (bukan kalimat generik).
        ->assertSee('wajib diisi')
        ->assertSee('tidak boleh bernilai negatif');

    // Input tidak dikosongkan saat validasi gagal (dapat dipulihkan).
    expect($component->instance()->debts)->toBe('-5')
        // Panel hasil tidak ditampilkan saat validasi gagal.
        ->and($component->instance()->showResult)->toBeFalse();
});

it('cek perhitungan valid tetap bekerja dengan versi mesin tercatat pada baris hasil', function () {
    $component = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('funeral', '800000')
        ->set('decedentGender', 'perempuan')
        ->set('spouse', 'husband')
        ->set('sons', 1)
        ->call('calculate');

    $result = $component->instance()->result;

    expect($result['engine_version'])->toBe(FaraidEngine::VERSION);
});
