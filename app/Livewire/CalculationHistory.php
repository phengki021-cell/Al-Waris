<?php

namespace App\Livewire;

use App\Models\Calculation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Riwayat Perhitungan'])]
class CalculationHistory extends Component
{
    /** @var array<int, bool> keterbukaan dialog konfirmasi per id */
    public array $confirmingDelete = [];

    /** @var array<int, bool> keterbukaan panel detail per id */
    public array $expandedDetails = [];

    public function toggleDetail(int $id): void
    {
        $this->expandedDetails[$id] = ! ($this->expandedDetails[$id] ?? false);
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete[$id] = true;
    }

    public function cancelDelete(int $id): void
    {
        unset($this->confirmingDelete[$id]);
    }

    /**
     * Hapus entri riwayat milik sesi ini saja (anonim via session token).
     */
    public function delete(int $id): void
    {
        $rows = Calculation::where('session_token', session()->getId())
            ->whereKey($id)
            ->get();

        DB::transaction(function () use ($rows): void {
            $rows->each->delete();
        });

        unset($this->confirmingDelete[$id]);
        session()->flash('message', 'Riwayat dihapus.');
    }

    public function render()
    {
        $calculations = Calculation::with(['participants.results'])
            ->where('session_token', session()->getId())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('livewire.calculation-history', [
            'calculations' => $calculations,
        ]);
    }
}
