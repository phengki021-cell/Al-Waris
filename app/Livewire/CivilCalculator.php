<?php

namespace App\Livewire;

use App\Models\CivilFamilyMember;
use App\Services\Civil\CivilInheritanceEngine;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CivilCalculator extends Component
{
    public array $result = [];

    public function analyze(): void
    {
        $token = session()->getId();
        $members = CivilFamilyMember::where('session_token', $token)->get();

        $data = [
            'pewaris' => $members->firstWhere('role', 'pewaris')?->toArray() ?? [],
            'pasangan' => $members->where('role', 'pasangan')->values()->toArray(),
            'anak' => $members->where('role', 'anak')->values()->toArray(),
            'ayah' => $members->firstWhere('role', 'ayah')?->toArray() ?? ['status' => 'tidak_diketahui'],
            'ibu' => $members->firstWhere('role', 'ibu')?->toArray() ?? ['status' => 'tidak_diketahui'],
            'saudara' => $members->where('role', 'saudara')->values()->toArray(),
        ];

        $engine = app(CivilInheritanceEngine::class);
        $this->result = $engine->analyze($data)->toArray();
    }

    public function render(): View
    {
        return view('livewire.civil-calculator');
    }
}