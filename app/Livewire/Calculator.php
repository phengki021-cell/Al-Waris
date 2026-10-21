<?php

namespace App\Livewire;

use App\Domain\Faraid\FaraidPreviewEngine;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Calculator extends Component
{
    use WithPagination;

    #[Validate('required|numeric|min:0|max:999999999999999.99')]
    public string $estateTotal = '';

    #[Validate('nullable|numeric|min:0')]
    public string $debtsTotal = '';

    #[Validate('nullable|numeric|min:0')]
    public string $bequestsTotal = '';

    public bool $hasHusband = false;

    public bool $hasWife = false;

    public bool $hasFather = false;

    public bool $hasMother = false;

    #[Validate('integer|min:0|max:30|required')]
    public int $sonsCount = 0;

    #[Validate('integer|min:0|max:30|required')]
    public int $daughtersCount = 0;

    public ?array $result = null;

    public function resetAll(): void
    {
        $this->reset();
    }

    public function calculate(): void
    {
        $this->validate();

        $engine = new FaraidPreviewEngine;
        $this->result = $engine->calculate(
            husband: $this->hasHusband ? 1 : 0,
            wife: $this->hasWife ? 1 : 0,
            sons: $this->sonsCount,
            daughters: $this->daughtersCount,
            father: $this->hasFather ? 1 : 0,
            mother: $this->hasMother ? 1 : 0,
            netEstate: max(
                0.0,
                (float) $this->estateTotal
                    - (float) $this->debtsTotal
                    - (float) $this->bequestsTotal
            ),
        );
    }

    public function render()
    {
        return view('livewire.calculator');
    }
}
