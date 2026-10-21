<?php

/**
 * Popup instalasi PWA (markup situs luas di layouts/app.blade.php + logika di
 * resources/js/app.js): tampil sekali per kunjungan pertama, tidak mengganggu
 * banner beranda eksisting maupun halaman /instalasi.
 */
it('popup instalasi PWA tersedia pada layout situs luas dan tetap tersembunyi bawaan', function () {
    /** @var string $html */
    $html = $this->get('/')->getContent();

    expect($html)->toContain('id="pwa-install-popup"')
        ->and($html)->toContain('Pasang Al-Waris di Perangkat Anda')
        ->and($html)->toContain('Instal Sekarang')
        ->and($html)->toContain('Nanti Saja')
        ->and($html)->toContain('data-pwa-popup-close')
        ->and($html)->toContain('/instalasi');

    // Popup tanpa JS harus tersembunyi: div tetap memuat atribut hidden.
    expect(preg_match('/id="pwa-install-popup"[^>]*hidden/is', $html))->toBe(1, 'Popup harus punya atribut hidden');
});

it('banner beranda PWA tetap berdiri sendiri tanpa diubah', function () {
    /** @var string $html */
    $html = $this->get('/')->getContent();

    expect($html)->toContain('id="pwa-install-banner"')
        ->and($html)->toContain('Instal Aplikasi Waris Syariah untuk Akses Cepat')
        ->and($html)->toContain('Instal Sekarang')
        ->and($html)->toContain('Nanti Saja');
});

it('app.js mengatur kunci kunjungan pertama, penundaan, dan tombol popup', function () {
    /** @var string $js */
    $js = file_get_contents(resource_path('js/app.js'));

    expect($js)->toContain('al-waris-install-popup')
        ->and($js)->toContain("querySelector('#pwa-install-popup')")
        ->and($js)->toContain("'seen'")
        ->and($js)->toContain('#pwa-popup-accept')
        ->and($js)->toContain('#pwa-popup-later')
        ->and($js)->toContain("'beforeinstallprompt'")
        ->and($js)->toContain('/instalasi');
});
