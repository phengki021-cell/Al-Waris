<?php

use App\Livewire\Calculator;
use App\Services\CivilInheritanceService;

/**
 * Tab opsional "Hukum Perdata — KUHPerdata" pada halaman Hitung Warisan.
 * Model edukasi Golongan I (bagian setara) — mesin Faraid utama TIDAK dipakai
 * dan tidak boleh berubah sama sekali.
 */
it('Golongan I: pasangan + 2 anak dari Rp 90 juta mendapat bagian setara Rp 30 juta', function () {
    /** @var CivilInheritanceService $service */
    $service = app(CivilInheritanceService::class);

    $result = $service->calculate([
        'estate' => '900000000',
        'spouse' => true,
        'children' => 2,
    ]);

    expect($result['status'])->toBe('valid')
        ->and($result['net_cents'])->toBe(90_000_000_000)
        ->and($result['total_cents'])->toBe(90_000_000_000)
        ->and($result['parts'])->toBe(3);

    /** @var list<array<string, mixed>> $entries */
    $entries = $result['entries'];

    expect(count($entries))->toBe(2)
        ->and($entries[0]['name'])->toBe('Pasangan yang masih hidup')
        ->and((int) $entries[0]['total_cents'])->toBe(30_000_000_000)
        ->and((int) $entries[0]['per_person_cents'])->toBe(30_000_000_000)
        ->and($entries[0]['unit_text'])->toBe('1/3')
        ->and($entries[1]['name'])->toBe('Anak yang masih hidup')
        ->and((int) $entries[1]['count'])->toBe(2)
        ->and((int) $entries[1]['total_cents'])->toBe(60_000_000_000)
        ->and((int) $entries[1]['per_person_cents'])->toBe(30_000_000_000)
        ->and($entries[1]['unit_text'])->toBe('1/3');
});

it('Golongan I: cabang anak wafat diwakili cucu satu bagian per cabang (Pasal 853)', function () {
    /** @var CivilInheritanceService $service */
    $service = app(CivilInheritanceService::class);

    $result = $service->calculate([
        'estate' => '900000000',
        'spouse' => true,
        'branches' => 1,
        'grandchildren' => 2,
    ]);

    expect($result['status'])->toBe('valid')
        ->and($result['parts'])->toBe(2);

    /** @var list<array<string, mixed>> $entries */
    $entries = $result['entries'];

    expect(count($entries))->toBe(2);

    // Pasangan: satu bagian setara.
    expect((int) $entries[0]['total_cents'])->toBe(45_000_000_000);

    // Cabang: satu bagian setara, dibagi dua cucu per orang.
    expect($entries[1]['name'])->toContain('Cabang anak yang wafat')
        ->and((int) $entries[1]['total_cents'])->toBe(45_000_000_000)
        ->and((int) $entries[1]['per_person_cents'])->toBe(22_500_000_000);
});

it('Golongan I: sisa sen dibagikan satu per satu ke urutan ahli waris', function () {
    /** @var CivilInheritanceService $service */
    $service = app(CivilInheritanceService::class);

    $result = $service->calculate([
        'estate' => '100.03',
        'spouse' => true,
        'children' => 2,
    ]);

    expect($result['status'])->toBe('valid')
        ->and($result['net_cents'])->toBe(10003);

    /** @var list<array<string, mixed>> $entries */
    $entries = $result['entries'];

    // 10003 sen / 3 satuan = 3334 sen tiap satuan; sisa 1 sen turun ke
    // satuan pertama (pasangan) sehingga total tetap 10003 sen.
    expect(count($entries))->toBe(2)
        ->and((int) $entries[0]['total_cents'])->toBe(3335)
        ->and((int) $entries[1]['total_cents'])->toBe(6668)
        ->and((int) $entries[1]['per_person_cents'])->toBe(3334)
        ->and($result['total_cents'])->toBe(10003);
});

it('Golongan I: pembulatan menghasilkan catatan terang dan rata per orang', function () {
    /** @var CivilInheritanceService $service */
    $service = app(CivilInheritanceService::class);

    $result = $service->calculate([
        'estate' => '100.99',
        'children' => 3,
    ]);

    expect($result['status'])->toBe('valid')
        ->and($result['net_cents'])->toBe(10099);

    /** @var list<array<string, mixed>> $entries */
    $entries = $result['entries'];

    // Tiga anak direkap satu baris kelompok: total 10099 sen,
    // rata per orang = 3366 sen.
    expect(count($entries))->toBe(1)
        ->and((int) $entries[0]['count'])->toBe(3)
        ->and((int) $entries[0]['total_cents'])->toBe(10099)
        ->and((int) $entries[0]['per_person_cents'])->toBe(3366)
        ->and($result['total_cents'])->toBe(10099);

    expect(implode(' ', $result['notes']))->toContain('Pembulatan');
});

it('Golongan I: menolak input kosong, tanpa ahli waris, negatif, dan kewajiban melebihi harta', function () {
    /** @var CivilInheritanceService $service */
    $service = app(CivilInheritanceService::class);

    $kosong = $service->calculate(['estate' => '', 'children' => 2]);

    expect($kosong['status'])->toBe('error')
        ->and($kosong['notes'][0])->toContain('kosong')
        ->and($kosong['entries'])->toBe([]);

    $tanpaAhli = $service->calculate(['estate' => '900000000']);

    expect($tanpaAhli['status'])->toBe('error')
        ->and($tanpaAhli['notes'][0])->toContain('Minimal satu ahli waris');

    $negatif = $service->calculate(['estate' => '900000000', 'children' => 2, 'debts' => '-1']);

    expect($negatif['status'])->toBe('error');

    $melebihi = $service->calculate(['estate' => '100', 'children' => 2, 'debts' => '200']);

    expect($melebihi['status'])->toBe('error')
        ->and($melebihi['notes'][0])->toContain('melebihi harta');

    foreach ([$kosong, $tanpaAhli, $negatif, $melebihi] as $result) {
        // Pasal KUHPerdata tetap disertakan pada kasus gagal sebagai rujukan edukasi.
        expect(count($result['pasals']))->toBeGreaterThan(0);
    }
});

it('tab mode perdata Livewire menghitung panel tersendiri dan tidak tersenggol faraid', function () {
    // Panel KUHPerdata: 90 juta, pasangan + 2 anak → 30 juta setiap satuan.
    $component = Livewire::test(Calculator::class)
        ->set('tab', 'civil')
        ->set('estate', '900000000')
        ->set('civilSpouse', true)
        ->set('civilChildren', 2)
        ->call('calculateCivil');

    expect($component->instance()->civilResult['status'])->toBe('valid')
        ->and($component->instance()->showCivilResult)->toBeTrue();

    $component->assertSee('Hasil Pembagian Perdata — Golongan I')
        ->assertSee('300.000.000,00')
        ->assertSee('600.000.000,00')
        ->assertSee('tidak disimpan ke Riwayat')
        ->assertSee('Pasal 852');

    // Validasi mode perdata tetap aktif.
    Livewire::test(Calculator::class)
        ->set('tab', 'civil')
        ->set('civilChildren', 2)
        ->call('calculateCivil')
        ->assertHasErrors('estate');

    // Faraid utama harus tetap utuh seperti sebelum perubahan apa pun.
    $faraid = Livewire::test(Calculator::class)
        ->set('estate', '9800000')
        ->set('funeral', '800000')
        ->set('spouse', 'husband')
        ->set('sons', 1)
        ->call('calculate');

    $result = $faraid->instance()->result;

    expect($result['status'])->toBe('valid')
        ->and($result['method'])->toBe('amal_ummi')
        ->and($result['total_cents'])->toBe(900_000_000);

    $faraid->assertSee('Hasil Pembagian')
        ->assertSee('2.250.000,00');
});

it('tab kontemporer menampilkan kartu yurisprudensi dan tautan jurnal', function () {
    Livewire::test(Calculator::class)
        ->set('tab', 'kontemporer')
        ->assertSee('Topik Kontemporer — peta rujukan')
        ->assertSee('Wasiat Wajibah Beda Agama (MA No. 16 K/AG/2010)')
        ->assertSee('Kewakilan Cucu: KHI Pasal 185 vs KUHPerdata Pasal 853')
        ->assertSee('Harta Bersama / Gono-Gini Sebelum Faraid')
        ->assertSee('Baca Jurnal Ilmiah Terkait')
        ->assertSee('scholar.google.com');
});
