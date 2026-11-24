<?php

namespace App\Livewire;

use App\Models\CivilBequest;
use App\Models\CivilWill;
use App\Models\CivilWillBeneficiary;
use App\Services\Civil\CivilInheritanceEngine;
use App\Services\Civil\CivilLegitimePortieAnalyzer;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CivilWasiatHibah extends Component
{
    public bool $hasWill = false;
    public ?string $willDate = null;
    public string $willForm = 'umum';
    public string $willNotes = '';

    /** @var array<int,array<string,mixed>> */
    public array $beneficiaries = [];

    /** @var array<int,array<string,mixed>> */
    public array $bequests = [];

    public array $legitimeResult = [];

    public function mount(): void
    {
        $this->beneficiaries = [];
        $this->bequests = [];
    }

    public function tambahPenerima(): void
    {
        $this->beneficiaries[] = ['name' => '', 'relation' => '', 'gift_type' => 'uang', 'object' => '', 'value' => null, 'notes' => ''];
    }

    public function hapusPenerima(int $i): void
    {
        unset($this->beneficiaries[$i]);
        $this->beneficiaries = array_values($this->beneficiaries);
    }

    public function tambahHibah(): void
    {
        $this->bequests[] = ['donor' => '', 'recipient' => '', 'relation' => '', 'given_at' => null, 'object' => '', 'asset_type' => '', 'value' => null, 'notes' => ''];
    }

    public function hapusHibah(int $i): void
    {
        unset($this->bequests[$i]);
        $this->bequests = array_values($this->bequests);
    }

    public function simpan(): void
    {
        $token = session()->getId();
        try {
            CivilWill::where('session_token', $token)->delete();
            CivilBequest::where('session_token', $token)->delete();

            $will = CivilWill::create([
                'session_token' => $token,
                'has_will' => $this->hasWill,
                'will_date' => $this->willDate,
                'will_form' => $this->hasWill ? $this->willForm : null,
                'notes' => $this->willNotes,
            ]);
            foreach ($this->beneficiaries as $b) {
                CivilWillBeneficiary::create([
                    'civil_will_id' => $will->id,
                    'name' => $b['name'] ?? null,
                    'relation' => $b['relation'] ?? null,
                    'gift_type' => $b['gift_type'] ?? null,
                    'object' => $b['object'] ?? null,
                    'value' => $b['value'] ?: null,
                    'notes' => $b['notes'] ?? null,
                ]);
            }
            foreach ($this->bequests as $b) {
                CivilBequest::create([
                    'session_token' => $token,
                    'donor' => $b['donor'] ?? null,
                    'recipient' => $b['recipient'] ?? null,
                    'relation' => $b['relation'] ?? null,
                    'given_at' => $b['given_at'] ?: null,
                    'object' => $b['object'] ?? null,
                    'asset_type' => $b['asset_type'] ?? null,
                    'value' => $b['value'] ?: null,
                    'notes' => $b['notes'] ?? null,
                ]);
            }
            $this->legitimeResult = (new CivilLegitimePortieAnalyzer())->analyze(['pewaris' => [], 'pasangan' => [], 'anak' => []]);
            session()->flash('status', 'Data wasiat & hibah tersimpan.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.civil-wasiat-hibah');
    }
}