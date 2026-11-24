<?php

namespace App\Livewire;

use App\Models\Calculation;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CivilHistory extends Component
{
    public function render(): View
    {
        $token = session()->getId();
        $items = Calculation::where('module', 'civil')
            ->where(function ($q) use ($token) {
                $q->where('session_token', $token)
                  ->orWhere('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('livewire.civil-history', ['items' => $items]);
    }
}