<?php

use Illuminate\Support\Facades\Route;

/**
 * cuplikan konten per halaman untuk navigasi menu edukasi Tahap 2.
 */
it('halaman publik seluruhnya dapat dibuka', function (string $route, string $marker) {
    $this->get(route($route))->assertOk()->assertSee($marker);
})->with([
    'Beranda' => ['home', 'Warisku'],
    'Ahli Waris' => ['heirs', 'Ahli Waris'],
    'Bagian-Bagian Warisan' => ['shares', 'Bagian'],
    'Cara Menghitung' => ['how-to', 'Menghitung'],
    'Kalkulator' => ['calculate', 'Hitung Warisan'],
    'Riwayat' => ['history', 'Riwayat Perhitungan'],
    'Tentang' => ['about', 'Tentang'],
    'Privasi' => ['privacy', 'Privasi'],
    'Referensi' => ['sources', 'Referensi'],
]);

it('seluruh rute bernama terdefinisi dan tidak mencekik', function () {
    foreach (['home', 'heirs', 'shares', 'how-to', 'calculate', 'history', 'about', 'privacy', 'sources'] as $name) {
        expect(Route::has($name))->toBeTrue("Rute '$name' harus ada");
    }
});
