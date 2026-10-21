<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function howTo(): View
    {
        return view('pages.how-to', [
            'steps' => config('furudh-steps.steps', []),
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
