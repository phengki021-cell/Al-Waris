<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

/**
 * Halaman "Cara Menghitung" (panduan kalkulasi): setiap tahapan dapat diklik
 * (accordion) memuat rumus, landasan fikih/kaidah, dan contoh studi kasus rupiah.
 */
beforeEach(function () {
    Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
});

it('halaman cara menghitung menampilkan seluruh tahapan yang dapat diklik', function () {
    $response = $this->get('/cara-menghitung');

    $response->assertOk()
        ->assertSee('Lihat Detail')
        ->assertSee('Penentuan Ahli Waris')
        ->assertSee('Pembagian Dzawil Furudh')
        ->assertSee('Ashabah (Menerima Sisa)')
        ->assertSee('Radd')
        ->assertSee('Pemisahan Harta Bersama')
        ->assertSee('Rumus')
        ->assertSee('Landasan Fikih')
        ->assertSee('Contoh Studi Kasus');
});

it('menampilkan studi kasus dengan angka rupiah yang dihitung eksak', function () {
    $response = $this->get('/cara-menghitung');

    $response->assertOk()
        ->assertSee('Rp 525.000.000')
        ->assertSee('Rp 87.500.000')
        ->assertSee('Rp 67.500.000')
        ->assertSee('Rp 120.000.000');
});

it('config tahapan memuat rumus, landasan, dan contoh lengkap', function () {
    /** @var array<string, array<string, mixed>> $steps */
    $steps = config('furudh-steps.steps', []);

    expect($steps)->toHaveKey('penentuan-ahli-waris')
        ->and($steps)->toHaveKey('dzawil-furudh')
        ->and($steps)->toHaveKey('asabah')
        ->and($steps)->toHaveKey('awal-radd')
        ->and($steps)->toHaveKey('harta-bersama');

    foreach ($steps as $code => $step) {
        expect(count((array) Arr::get($step, 'rumus', [])))->toBeGreaterThan(0, "Rumus kosong: {$code}")
            ->and(count((array) Arr::get($step, 'landasan', [])))->toBeGreaterThan(0, "Landasan kosong: {$code}")
            ->and((string) Arr::get($step, 'ringkasan'))->toBeString();

        $kasusItems = [
            Arr::get($step, 'contoh'),
            Arr::get($step, 'contoh_aul'),
            Arr::get($step, 'contoh_radd'),
        ];

        $jumlahKasus = 0;

        foreach ($kasusItems as $kasus) {
            if (! is_array($kasus)) {
                continue;
            }

            $jumlahKasus++;

            expect(is_string(Arr::get($kasus, 'harta_awal')) && str_contains((string) Arr::get($kasus, 'harta_awal'), 'Rp'))->toBeTrue("Harta tidak rupiah: {$code}")
                ->and(count((array) Arr::get($kasus, 'langkah', [])))->toBeGreaterThan(2, "Langkah kurang: {$code}")
                ->and(str_contains((string) Arr::get($kasus, 'hasil'), 'Rp'))->toBeTrue("Hasil tidak rupiah: {$code}");
        }

        expect($jumlahKasus)->toBeGreaterThan(0, "Contoh kasus kosong: {$code}");
    }
});
