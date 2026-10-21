<?php

use App\Livewire\CalculationHistory;
use App\Models\Calculation;
use App\Models\CalculationParticipant;
use Database\Factories\CalculationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('riwayat menampilkan entri milik sesi ini saja', function () {
    $tokenSaya = session()->getId();

    CalculationFactory::new()->create(['session_token' => $tokenSaya, 'reference_code' => 'ALW-MILIK-SAYA-1']);
    CalculationFactory::new()->create(['session_token' => 'token-orang-lain', 'reference_code' => 'ALW-ORANG-LAIN-9']);

    $component = Livewire::test(CalculationHistory::class);

    $component->assertSee('ALW-MILIK-SAYA-1')
        ->assertDontSee('ALW-ORANG-LAIN-9');
});

it('menolak menghapus entri sesi lain dan dapat menghapus milik sendiri', function () {
    $tokenSaya = session()->getId();

    $punyaOrang = CalculationFactory::new()->create(['session_token' => 'token-orang-lain']);

    $milikSaya = CalculationFactory::new()->create(['session_token' => $tokenSaya]);
    CalculationParticipant::create([
        'calculation_id' => $milikSaya->id,
        'code' => 'husband',
        'name_id' => 'Suami',
        'count' => 1,
        'sort_order' => 1,
    ]);

    // Coba hapus milik orang lain → tidak apa-apa (tidak ditemukan di sesi ini).
    Livewire::test(CalculationHistory::class)->call('delete', $punyaOrang->id);
    expect(Calculation::count())->toBe(2);

    // Hapus milik sendiri → terhapus (cascade ke peserta).
    Livewire::test(CalculationHistory::class)
        ->call('confirmDelete', $milikSaya->id)
        ->call('delete', $milikSaya->id);

    expect(Calculation::whereKey($milikSaya->id)->count())->toBe(0)
        ->and(Calculation::whereSessionToken('token-orang-lain')->count())->toBe(1);
});

it('riwayat menyimpan dan menampilkan versi mesin hitung serta detail dapat dibuka', function () {
    $token = session()->getId();

    CalculationFactory::new()->create([
        'session_token' => $token,
        'reference_code' => 'ALW-2026-000100',
        'engine_version' => '0.3.0',
    ]);

    $component = Livewire::test(CalculationHistory::class)
        ->assertSee('ALW-2026-000100')
        ->call('toggleDetail', 1)
        ->assertSee('v0.3.0')
        ->assertSee('Harta bersih');

    $component->call('toggleDetail', 1);

    expect($component->instance()->expandedDetails[1])->toBeFalse();
});
