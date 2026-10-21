<?php

namespace App\Http\Controllers;

use App\Models\Heir;
use Illuminate\View\View;

class HeirCatalogController extends Controller
{
    public function __invoke(): View
    {
        $heirs = Heir::active()
            ->with('shares', 'source')
            ->orderBy('category')
            ->orderBy('name_id')
            ->get()
            ->groupBy('category');

        return view('pages.heirs', [
            'heirs' => $heirs,
            'categories' => [
                'fard' => 'Dzawil Furudh (bagian pasti)',
                'asabah' => 'Ashabah (menerima sisa)',
                'arham' => 'Dzawil Arham (kerabat)',
                'hajib' => 'Penghalang (hajib)',
            ],
        ]);
    }
}
