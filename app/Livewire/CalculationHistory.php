<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CalculationHistory extends Component
{
    use WithPagination;

    public function render()
    {
        // Tahap 1: belum ada penyimpanan riwayat penuh; empty state.
        return view('livewire.calculation-history', [
            'calculations' => collect(), // Tahap 3: Calculation::latest()->paginate(),
        ]);
    }
}
