<?php

use App\Livewire\HeirExplorer;
use App\Livewire\ShareExplorer;
use App\Models\Heir;
use App\Models\Share;
use App\Services\ReferenceService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;

/**
 * Blok rujukan tambahan pada modal edukasi (Ahli Waris + Bagian Warisan) dan
 * halaman Cara Menghitung: hadis shahih, pasal KUHPerdata (mode perdata),
 * yurisprudensi MA RI, dan "Baca Jurnal Ilmiah Terkait" — semua link eksternal
 * HTTPS yang telah diverifikasi.
 */
beforeEach(function () {
    Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
});

it('config memuat hadis shahih dengan teks Arab, Latin, dan terjemahan lengkap', function () {
    $hadits = config('furudh-dalil.hadits');

    expect($hadits)->toBeArray()
        ->and(Arr::get($hadits, 'primary.label'))->toContain('at-Tirmidzi No. 2120')
        ->and(Arr::get($hadits, 'primary.arab'))->toBeString()
        ->and(strlen((string) Arr::get($hadits, 'primary.arab')))->toBeGreaterThan(20)
        ->and(Arr::get($hadits, 'primary.latin'))->toBeString()
        ->and(strlen((string) Arr::get($hadits, 'primary.latin')))->toBeGreaterThan(20)
        ->and(Arr::get($hadits, 'primary.terjemah'))->toBeString()
        ->and(Arr::get($hadits, 'primary.sumber'))->toContain('at-Tirmidzi')
        ->and(count((array) Arr::get($hadits, 'rumusan', [])))->toBeGreaterThan(0);
});

it('config yurisprudensi lengkap dengan link Direktori Putusan & JDIH MA RI', function () {
    $yurisprudensi = (array) config('furudh-dalil.yurisprudensi', []);

    expect(array_keys($yurisprudensi))->toContain('MA-16-K-AG-2010', 'MA-51-K-AG-1999', 'SEMA-2-TAHUN-1994')
        ->and(Arr::get($yurisprudensi, 'MA-16-K-AG-2010.label'))->toContain('16 K/AG/2010')
        ->and(Arr::get($yurisprudensi, 'MA-16-K-AG-2010.link'))->toContain('putusan3.mahkamahagung.go.id')
        ->and(Arr::get($yurisprudensi, 'MA-51-K-AG-1999.label'))->toContain('51 K/AG/1999')
        ->and(Arr::get($yurisprudensi, 'SEMA-2-TAHUN-1994.label'))->toContain('SEMA No. 2 Tahun 1994');

    foreach ($yurisprudensi as $key => $entry) {
        expect(Arr::get($entry, 'link'))->toStartWith('https://', "Link yurisprudensi $key");
        expect(Arr::get($entry, 'ringkas'))->toBeString("Ringkas $key");
    }
});

it('config KUHPerdata menyiapkan parafrase pasal 832–853 dan penggolongan I–IV', function () {
    $kuhperdata = (array) config('furudh-dalil.kuhperdata', []);

    expect((string) Arr::get($kuhperdata, 'keterangan'))->toContain('parafrase');

    /** @var array<int|string, mixed> $pasal */
    $pasal = (array) Arr::get($kuhperdata, 'pasal', []);

    // Catatan: PHP memaksa kunci array numerik ("832") menjadi int.
    expect(count($pasal))->toBe(4)
        ->and(Arr::get($pasal, '832.label'))->toBe('KUHPerdata Pasal 832 ayat (1)')
        ->and(Arr::get($pasal, '852.label'))->toBe('KUHPerdata Pasal 852 (Golongan I)')
        ->and(Arr::get($pasal, '852-b.label'))->toBe('KUHPerdata Pasal 852 huruf b (catatan perkawinan kedua)')
        ->and(Arr::get($pasal, '853.label'))->toBe('KUHPerdata Pasal 853 (Penggantian Tempat)');

    foreach ($pasal as $key => $entry) {
        expect((string) Arr::get($entry, 'label'))->toBeString("Label pasal $key")
            ->and(strlen((string) Arr::get($entry, 'teks')))->toBeGreaterThan(20, "Teks pasal $key terlalu pendek");
    }

    expect((array) Arr::get($kuhperdata, 'golongan', []))->toHaveCount(5);
});

it('config jurnal hanya memuat tautan https yang dapat dipakai blok Baca Jurnal', function () {
    /** @var array<string, array<string, mixed>> $jurnal */
    $jurnal = (array) config('furudh-dalil.jurnal', []);

    expect(count($jurnal))->toBeGreaterThanOrEqual(6)
        ->and(array_keys($jurnal))->toContain('WASIAT-WAJIBAH-2010', 'FARAID-KHI-SCHOLAR', 'FARAID-KHI-SINTA', 'KUHPERDATA-UMSU');

    foreach ($jurnal as $key => $entry) {
        expect((string) Arr::get($entry, 'href'))->toStartWith('https://', "Link jurnal $key")
            ->and((string) Arr::get($entry, 'label'))->toBeString("Label jurnal $key");
    }
});

it('ReferenceService memetakan kartu kontemporer ke entry jurnal yang valid', function () {
    /** @var ReferenceService $references */
    $references = app(ReferenceService::class);

    /** @var array<int, array<string, mixed>> $cards */
    $cards = $references->kontemporer();

    expect(count($cards))->toBe(4);

    foreach ($cards as $card) {
        expect((string) Arr::get($card, 'judul'))->toBeString()
            ->and(strlen((string) Arr::get($card, 'ringkas')))->toBeGreaterThan(30)
            ->and(count((array) Arr::get($card, 'rujukan', [])))->toBeGreaterThan(0, "Rujukan kosong: {$card['judul']}");

        $keys = array_values(array_filter(
            is_array($card['jurnal'] ?? null) ? $card['jurnal'] : [],
            'is_string',
        ));

        $entries = $references->jurnal($keys, withDefaults: false);

        expect(count($entries))->toBeGreaterThan(0, "Jurnal tak terpetakan: {$card['judul']}");

        foreach ($entries as $entry) {
            expect((string) $entry['href'])->toStartWith('https://');
        }
    }
});

it('modal ahli waris menampilkan hadis, yurisprudensi MA, jurnal, dan KUHPerdata', function () {
    /** @var Heir $father */
    $father = Heir::query()->where('code', 'father')->firstOrFail();

    /** @var Heir $husband */
    $husband = Heir::query()->where('code', 'husband')->firstOrFail();

    foreach ([$father, $husband] as $heir) {
        Livewire::test(HeirExplorer::class)
            ->call('openDetail', $heir->id)
            ->assertSee('Hadis Shahih Terkait Warisan')
            ->assertSee('at-Tirmidzi No. 2120')
            ->assertSee('Yurisprudensi Mahkamah Agung RI')
            ->assertSee('16 K/AG/2010')
            ->assertSee('Baca Jurnal Ilmiah Terkait')
            ->assertSee('Portal SINTA')
            ->assertSee('Pasal KUHPerdata — Mode Hukum Waris Perdata / Non-Muslim');
    }
});

it('modal ahli waris ayah memuat yurisprudensi khusus SEMA No. 2 Tahun 1994', function () {
    /** @var Heir $father */
    $father = Heir::query()->where('code', 'father')->firstOrFail();

    Livewire::test(HeirExplorer::class)
        ->call('openDetail', $father->id)
        ->assertSee('SEMA No. 2 Tahun 1994');
});

it('modal bagian warisan juga memuat blok rujukan lengkap', function () {
    /** @var Share $share */
    $share = Share::query()
        ->whereHas('heir', fn ($query) => $query->where('code', 'husband'))
        ->where('numerator', 1)
        ->where('denominator', 2)
        ->firstOrFail();

    Livewire::test(ShareExplorer::class)
        ->call('openDetail', $share->id)
        ->assertSee('Hadis Shahih Terkait Warisan')
        ->assertSee('at-Tirmidzi No. 2120')
        ->assertSee('Yurisprudensi Mahkamah Agung RI')
        ->assertSee('16 K/AG/2010')
        ->assertSee('Baca Jurnal Ilmiah Terkait')
        ->assertSee('KUHPerdata');
});

it('halaman cara menghitung menyediakan pusat rujukan MA dan jurnal', function () {
    $this->get('/cara-menghitung')
        ->assertOk()
        ->assertSee('Pusat Rujukan')
        ->assertSee('Yurisprudensi')
        ->assertSee('16 K/AG/2010')
        ->assertSee('Baca Jurnal Ilmiah Terkait');
});

it('halaman referensi memuat rujukan digital MA dan jurnal ilmiah', function () {
    $this->get('/referensi')
        ->assertOk()
        ->assertSee('Rujukan Digital — Yurisprudensi & Jurnal Ilmiah')
        ->assertSee('16 K/AG/2010')
        ->assertSee('putusan3.mahkamahagung.go.id')
        ->assertSee('Studi Analisis Putusan MA Nomor 16 K/AG/2010')
        ->assertSee('Portal SINTA');
});
