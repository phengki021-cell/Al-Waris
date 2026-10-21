<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.sources', [
            'sources' => Source::orderBy('type')->orderBy('title')->get(),
        ]);
    }
}
