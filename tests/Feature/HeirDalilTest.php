<?php

use App\Livewire\HeirExplorer;
use App\Models\Heir;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;

/**
 * Modal "Lihat Detail" pada halaman Ahli Waris: kartu klikable berisi
 * definisi, syarat, kondisi mahrum/hijab, dalil QS (Arab/Latin/Kemenag) + KHI.
 */
beforeEach(function () {
    Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
});

it('halaman ahli waris menampilkan kartu klikable dengan indikator Lihat Detail', function () {
    $response = $this->get('/ahli-waris');

    $response->assertOk()
        ->assertSee('Lihat Detail')
        ->assertSee('Dzawil Furudh (bagian pasti)')
        ->assertSee('Ashabah (menerima sisa)');
});

it('modal detail ahli waris menampilkan definisi, syarat, hijab, dalil, dan KHI', function () {
    /** @var Heir $heir */
    $heir = Heir::query()->where('code', 'husband')->firstOrFail();

    Livewire::test(HeirExplorer::class)
        ->call('openDetail', $heir->id)
        ->assertSee('Detail Ahli Waris — Suami')
        ->assertSee('Definisi')
        ->assertSee('Syarat Mendapatkan Warisan')
        ->assertSee('Kondisi Mahrum / Hijab (Penghalang)')
        ->assertSee('Rujukan KHI (Inpres No. 1 Tahun 1991)')
        ->assertSee('QS. An-Nisaa: 12')
        ->assertSee('KHI Pasal 179');

    Livewire::test(HeirExplorer::class)
        ->call('openDetail', $heir->id)
        ->call('closeDetail')
        ->assertDontSee('Detail Ahli Waris — Suami');
});

it('setiap ahli waris pada database terpetakan profil dan dalil lengkap', function () {
    /** @var Collection<int, Heir> $heirs */
    $heirs = Heir::query()->active()->get();
    /** @var array<string, mixed> $verses */
    $verses = config('furudh-dalil.verses', []);
    /** @var array<int, mixed> $khi */
    $khi = config('furudh-dalil.khi', []);

    expect($heirs->count())->toBeGreaterThan(0);

    foreach ($heirs as $heir) {
        $entry = config('furudh-heirs.heirs.'.$heir->code);

        expect($entry)->not->toBeNull("Profil config tidak ada untuk kode: {$heir->code}")
            ->and(Arr::get($entry, 'definisi'))->toBeString()
            ->and(count((array) Arr::get($entry, 'syarat', [])))->toBeGreaterThan(0, "Syarat kosong: {$heir->code}")
            ->and(count((array) Arr::get($entry, 'menghalangi', [])))->toBeGreaterThan(0, "Mahrum kosong: {$heir->code}")
            ->and(count((array) Arr::get($entry, 'dihalangi', [])))->toBeGreaterThan(0, "Dihalangi kosong: {$heir->code}");

        $verseCode = Arr::get($entry, 'dalil.verse');

        if (is_string($verseCode)) {
            expect(Arr::get($verses, $verseCode))->not->toBeNull("Ayat tidak ada untuk {$heir->code}");
        }

        foreach (Arr::get($entry, 'dalil.pasals', []) as $pasal) {
            expect(Arr::get($khi, $pasal))->not->toBeNull("Pasal tidak ada untuk {$heir->code}");
        }
    }
});

it('config profil ahli waris memuat ayat kemenag dan pasal khi yang valid', function () {
    /** @var array<string, array<string, string>> $verses */
    $verses = config('furudh-dalil.verses', []);
    /** @var array<int, array<string, string>> $khi */
    $khi = config('furudh-dalil.khi', []);

    foreach (config('furudh-heirs.heirs', []) as $code => $entry) {
        $verseCode = Arr::get($entry, 'dalil.verse');

        if (is_string($verseCode)) {
            $verse = Arr::get($verses, $verseCode);

            expect($verse)->toBeArray("Ayat {$verseCode} untuk {$code}")
                ->and(strlen((string) Arr::get($verse, 'arab')))->toBeGreaterThan(50)
                ->and((string) Arr::get($verse, 'terjemah'))->toContain('Allah');
        }

        foreach (Arr::get($entry, 'dalil.pasals', []) as $pasal) {
            expect(strlen((string) Arr::get($khi[$pasal], 'teks')))->toBeGreaterThan(20, "Pasal {$pasal} ({$code}) terlalu pendek");
        }

        expect(str_contains((string) Arr::get($entry, 'definisi'), 'pewaris'))->toBeTrue("Definisi {$code} tidak menyebut pewaris");
    }
});
