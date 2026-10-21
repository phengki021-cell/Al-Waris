<?php

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

it('halaman 404 informatif berbahasa Indonesia', function () {
    $this->get('/halaman-tidak-ada-xyz')
        ->assertNotFound()
        ->assertSee('Halaman tidak ditemukan')
        ->assertSee('Ke Beranda');
});

it('menyajikan manifest dengan ikon disk yang ada', function () {
    foreach (['icon-192.png', 'icon-512.png', 'maskable-192.png', 'maskable-512.png', 'apple-touch-icon-180.png', 'favicon-48.png'] as $icon) {
        expect(is_file(public_path("icons/{$icon}")))->toBeTrue("Ikon {$icon} harus ada");
    }
});
