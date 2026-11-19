<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('pages.settings');
    }

    public function setLocale(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:id,en,ar'],
        ]);

        $locale = $validated['locale'];
        $request->session()->put('locale', $locale);

        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}