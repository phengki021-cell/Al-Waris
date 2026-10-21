<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Heir;
use App\Models\Share;
use App\Models\Source;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'heirsCount' => Heir::active()->count(),
            'sharesCount' => Share::count(),
            'sourcesCount' => Source::count(),
            'demoCount' => Calculation::count(),
        ]);
    }
}
