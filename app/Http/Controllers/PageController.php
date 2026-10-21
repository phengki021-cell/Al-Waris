<?php

namespace App\Http\Controllers;

use App\Services\ReferenceService;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class PageController extends Controller
{
    public function howTo(): View
    {
        $steps = (array) config('furudh-steps.steps', []);
        $reference = app(ReferenceService::class);

        // Rujukan per tahap: key step → daftar yurisprudensi & jurnal.
        $stepReferences = [];

        foreach ($steps as $code => $step) {
            $rujukan = Arr::get($step, 'rujukan', []) ?? [];

            $stepReferences[$code] = [
                'yurisprudensi' => $reference->yurisprudensi(
                    array_values(array_filter((array) Arr::get($rujukan, 'yurisprudensi', []), 'is_string')),
                    false,
                ),
                'jurnal' => $reference->jurnal(
                    array_values(array_filter((array) Arr::get($rujukan, 'jurnal', []), 'is_string')),
                    false,
                ),
            ];
        }

        return view('pages.how-to', [
            'steps' => $steps,
            'stepReferences' => $stepReferences,
            'reference' => $reference,
            'referenceHubJurnal' => $reference->semuaJurnal(),
            'referenceHubYurisprudensi' => $reference->semuaYurisprudensi(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}
