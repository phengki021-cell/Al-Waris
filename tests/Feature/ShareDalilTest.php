<?php

use App\Livewire\ShareExplorer;
use App\Models\Share;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;

/**
 * Modal "Lihat Detail Dalil" pada halaman Bagian-Bagian Warisan: kartu klikable,
 * data dalil QS An-Nisaa (Arab/Latin/Terjemahan Kemenag) + KHI + syarat + contoh.
 */
beforeEach(function () {
    Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
});

it('halaman bagian warisan menampilkan kartu yang dapat diklik dan tombol detail', function () {
    $response = $this->get('/bagian-warisan');

    $response->assertOk()
        ->assertSee('Lihat Detail Dalil')
        ->assertSee('dzawil furudh');
});

it('modal detail terbuka dengan dalil QS dan KHI sesuai bagian yang dipilih', function () {
    /** @var Share $share */
    $share = Share::query()
        ->whereHas('heir', fn ($query) => $query->where('code', 'husband'))
        ->where('numerator', 1)
        ->where('denominator', 2)
        ->firstOrFail();

    Livewire::test(ShareExplorer::class)
        ->call('openDetail', $share->id)
        ->assertSee('Detail Dalil — Suami')
        ->assertSee('QS. An-Nisaa: 12')
        ->assertSee('KHI Pasal 179')
        ->assertSee('Rp 20.000.000')
        ->assertSee('Ketentuan (Dzawil Furudh)')
        ->assertSee('Contoh Ilustrasi Hitungan');

    Livewire::test(ShareExplorer::class)
        ->call('openDetail', $share->id)
        ->call('closeDetail')
        ->assertDontSee('Detail Dalil — Suami');
});

it('setiap data bagian pada database terpetakan dalil lengkap', function () {
    /** @var Collection<int, Share> $shares */
    $shares = Share::with('heir')->get();

    expect($shares->count())->toBeGreaterThan(0);

    foreach ($shares as $share) {
        $heirCode = $share->heir?->code;

        expect($heirCode)->toBeString();

        $key = $heirCode.':'.$share->toText();
        $entry = config("furudh-dalil.map.{$key}");
        $verseCode = Arr::get($entry, 'verse');

        expect($entry)->not->toBeNull("Pasang dalil untuk kunci config: {$key}")
            ->and($verseCode)->toBeString()
            ->and((array) config('furudh-dalil.verses', []))->toHaveKey((string) $verseCode);
    }
});

it('config furudh-dalil memuat ayat resmi dan pasal KHI yang valid', function () {
    /** @var array<string, array<string, string>> $verses */
    $verses = config('furudh-dalil.verses', []);
    /** @var array<string, array<string, string>> $khi */
    $khi = config('furudh-dalil.khi', []);

    expect($verses)->toHaveKey('QURAN-4-11')
        ->and($verses)->toHaveKey('QURAN-4-12')
        ->and($verses)->toHaveKey('QURAN-4-176');

    foreach ($verses as $kode => $ayat) {
        expect(Arr::get($ayat, 'arab'))->toBeString()
            ->and(strlen((string) Arr::get($ayat, 'arab')))->toBeGreaterThan(50, "Arab {$kode} terlalu pendek")
            ->and(strlen((string) Arr::get($ayat, 'latin')))->toBeGreaterThan(50)
            ->and((string) Arr::get($ayat, 'terjemah'))->toContain('Allah')
            ->and(Arr::get($ayat, 'footnote'))->toBeString();
    }

    // Pasal yang dirujuk peta harus benar-benar ada di bagian 'khi'
    foreach (config('furudh-dalil.map', []) as $key => $entry) {
        foreach (Arr::get($entry, 'pasals', []) as $pasal) {
            expect($khi)->toHaveKey((string) $pasal)
                ->and(Arr::get($khi[$pasal], 'judul'))->toBeString()
                ->and(strlen((string) Arr::get($khi[$pasal], 'teks')))->toBeGreaterThan(20, "Pasal {$pasal} tidak ada (kunci {$key})");
        }
    }
});
