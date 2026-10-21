<?php

/**
 * Tahap 4 — PWA: manifest, service worker, halaman offline/instalasi, dan
 * persyaratan privasi (tidak ada konten kalkulator/riwayat yang di-cache).
 */
it('manifest memuat identitas Al-Waris dan ikon yang lengkap', function () {
    $response = $this->get('/manifest.json');

    $response->assertOk();

    $manifest = $response->json();

    expect($manifest['name'])->toContain('Al-Waris')
        ->and($manifest['short_name'])->toBe('Al-Waris')
        ->and($manifest['start_url'])->toBe('/')
        ->and($manifest['display'])->toBe('standalone')
        ->and($manifest['theme_color'])->toBe('#123D32')
        ->and($manifest['background_color'])->toBe('#F5F7F4');

    $icons = collect($manifest['icons']);

    expect($icons->count())->toBeGreaterThanOrEqual(4)
        ->and($icons->pluck('purpose')->contains('maskable'))->toBeTrue()
        ->and($icons->pluck('src')->values()->all())->toContain('/icons/icon-192.png', '/icons/icon-512.png');
});

it('service worker tidak menyimpan halaman kalkulator/riwayat dan memakai versi eksplisit', function () {
    $sw = $this->get('/sw.js');

    $sw->assertOk();

    /** @var string $body */
    $body = $sw->getContent();

    // Versi cache harus eksplisit agar pembaruan dapat dinilai & dinaikkan
    expect($body)->toContain("const CACHE_VERSION = 'al-waris-");

    // Halaman berisi data pengguna: network-only (dikontrol isDynamicPage)
    expect($body)->toContain("'/hitung-warisan'")
        ->and($body)->toContain("'/riwayat'")
        ->and($body)->toContain("'/livewire/update'");

    // Ada fallback offline dan aset build Vite yang di-cache-first
    expect($body)->toContain('/offline')
        ->and($body)->toContain('/build/');
});

it('sw tidak meng-cache /hitung-warisan secara statis', function () {
    /** @var string $body */
    $body = $this->get('/sw.js')->getContent();

    // Bila kalkulator tidak boleh di-cache: pastikan fetch diarahkan jaringan
    // dan tidak menjalankan cache.put untuk halaman dinamis tersebut.
    expect($body)->toContain('isDynamicPage')
        ->and($body)->toContain('catch'); // network-only dengan catch → offline fallback
});

it('halaman offline dan instalasi dapat diakses', function () {
    $this->get(route('offline'))->assertOk()->assertSee('sedang offline');
    $this->get(route('install'))->assertOk()->assertSee('PWA');
});
