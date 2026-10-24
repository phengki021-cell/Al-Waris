<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * cuplikan konten per halaman untuk navigasi menu edukasi Tahap 2.
 */
it('halaman publik seluruhnya dapat dibuka', function (string $route, string $marker) {
    $this->get(route($route))->assertOk()->assertSee($marker);
})->with([
    'Beranda' => ['home', 'Al-Waris'],
    'Ahli Waris' => ['heirs', 'Ahli Waris'],
    'Bagian-Bagian Warisan' => ['shares', 'Bagian'],
    'Cara Menghitung' => ['how-to', 'Menghitung'],
    'Kalkulator' => ['calculate', 'Hitung Warisan'],
    'Riwayat' => ['history', 'Riwayat Perhitungan'],
    'Tentang' => ['about', 'Tentang'],
    'Privasi' => ['privacy', 'Privasi'],
    'Referensi' => ['sources', 'Referensi'],
    'Instalasi PWA' => ['install', 'Pasang Al-Waris'],
    'Offline' => ['offline', 'offline'],
]);

it('seluruh rute bernama terdefinisi dan tidak mencekik', function () {
    foreach (['home', 'heirs', 'shares', 'how-to', 'calculate', 'history', 'about', 'privacy', 'sources', 'install', 'offline', 'manifest', 'sw'] as $name) {
        expect(Route::has($name))->toBeTrue("Rute '$name' harus ada");
    }
});

it('menampilkan kredit pengembang Hengki Pranata di halaman publik', function (string $route) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee('Hengki Pranata')
        ->assertSee('Warisan Islam, Untuk Masa Depan');
})->with([
    'Beranda' => 'home',
    'Tentang' => 'about',
    'Kalkulator' => 'calculate',
]);

it('halaman tentang menyajikan seksi Tentang Pengembang', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Tentang Pengembang')
        ->assertSee('Dikembangkan oleh')
        ->assertSee('HP');
});

it('halaman 404 informatif berbahasa Indonesia', function () {
    $this->get('/halaman-tidak-ada-xyz')
        ->assertNotFound()
        ->assertSee('Halaman tidak ditemukan')
        ->assertSee('Ke Beranda');
});

it('halaman ahli waris menyajikan materi ashabah, hijab, dan ahli waris pengganti', function () {
    $this->get(route('heirs'))
        ->assertOk()
        ->assertSee('Ashabah')
        ->assertSee('bil ghair')
        ->assertSee('bertakhsis')
        ->assertSee('Hijab')
        ->assertSee('mahjub')
        ->assertSee('Hijab nuqshan')
        ->assertSee('Ahli Waris Pengganti')
        ->assertSee('Mawla')
        ->assertSee('Musafir al-Islam');
});

it('data istri menampilkan bagian maksimum 1/4 dan saudara sedarah laki-laki berkategori asabah', function () {
    $this->seed(Database\Seeders\HeirSeeder::class);

    $istri = App\Models\Heir::query()->where('code', 'wife')->first();
    expect($istri)->not->toBeNull()
        ->and((int) $istri->max_share_numerator)->toBe(1)
        ->and((int) $istri->max_share_denominator)->toBe(4);

    $sedarah = App\Models\Heir::query()->where('code', 'sibling_male_full')->first();
    expect($sedarah)->not->toBeNull()
        ->and($sedarah->category)->toBe('asabah')
        ->and($sedarah->max_share_numerator)->toBeNull();
});

it('menyajikan manifest dengan ikon disk yang ada', function () {
    foreach (['icon-192.png', 'icon-512.png', 'maskable-192.png', 'maskable-512.png', 'apple-touch-icon-180.png', 'favicon-48.png'] as $icon) {
        expect(is_file(public_path("icons/{$icon}")))->toBeTrue("Ikon {$icon} harus ada");
    }
});
