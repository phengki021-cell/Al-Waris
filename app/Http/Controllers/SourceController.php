<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Services\ReferenceService;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function __invoke(): View
    {
        $reference = app(ReferenceService::class);

        return view('pages.sources', [
            'sources' => Source::orderBy('type')->orderBy('title')->get(),
            'jurnals' => $reference->semuaJurnal(),
            'yurisprudensis' => $reference->semuaYurisprudensi(),
        ]);
    }
}
