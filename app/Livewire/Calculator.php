<?php

namespace App\Livewire;

use App\Domain\Faraid\EstateDistribution;
use App\Services\EstateCalculationService;
use App\Services\SaveCalculationService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Calculator extends Component
{
    /** @var array<string, list<string>> */
    private const RULES = [
        'estate' => ['required', 'numeric', 'min:0'],
        'debts' => ['nullable', 'numeric', 'min:0'],
        'bequest' => ['nullable', 'numeric', 'min:0'],
        'funeral' => ['nullable', 'numeric', 'min:0'],
        'spouse' => ['nullable', 'in:husband,wife'],
        'wifeCount' => ['integer', 'min:1', 'max:4'],
        'sons' => ['integer', 'min:0', 'max:30'],
        'daughters' => ['integer', 'min:0', 'max:30'],
        'maternalGrandmother' => ['boolean'],
        'brothers' => ['integer', 'min:0', 'max:30'],
        'sisters' => ['integer', 'min:0', 'max:30'],
    ];

    // --- Nominal ---
    public string $estate = '';

    public string $debts = '';

    public string $bequest = '';

    public string $funeral = '';

    // --- Ahli waris ---
    public string $spouse = '';            // '' | 'husband' | 'wife'

    public int $wifeCount = 1;

    public int $sons = 0;

    public int $daughters = 0;

    public bool $father = false;

    public bool $mother = false;

    public bool $maternalGrandmother = false;

    public int $brothers = 0;

    public int $sisters = 0;

    public ?array $result = null;

    public function resetAll(): void
    {
        $this->reset();
    }

    public function calculate(): void
    {
        $this->validate([...self::RULES]);

        $distribution = $this->buildDistribution();

        $this->result = $distribution->toDisplayArray();
    }

    public function saveToHistory(): void
    {
        if (! $this->result || ($this->result['status'] ?? '') !== 'valid') {
            return;
        }

        app(SaveCalculationService::class)->save(
            $this->buildDistribution(),
            session()->getId(),
        );

        session()->flash('message', 'Hasil disimpan ke Riwayat Perhitungan.');
    }

    /**
     * Input Livewire → service → EstateDistribution.
     * Pola tunggal dipakai baik untuk tampilan hasil maupun penyimpanan,
     * sehingga yang disimpan selalu sama dengan yang ditampilkan.
     */
    private function buildDistribution(): EstateDistribution
    {
        return app(EstateCalculationService::class)->calculate(
            heirs: [
                'husband' => $this->spouse === 'husband',
                'wife_count' => $this->spouse === 'wife' ? $this->wifeCount : 0,
                'sons' => $this->sons,
                'daughters' => $this->daughters,
                'father' => $this->father,
                'mother' => $this->mother,
                'maternal_grandmother' => $this->maternalGrandmother,
                'brothers' => $this->brothers,
                'sisters' => $this->sisters,
            ],
            money: [
                'estate' => (float) ($this->estate ?: 0),
                'debts' => (float) ($this->debts ?: 0),
                'bequest' => (float) ($this->bequest ?: 0),
                'funeral' => (float) ($this->funeral ?: 0),
            ],
        );
    }

    public function render()
    {
        return view('livewire.calculator');
    }
}
