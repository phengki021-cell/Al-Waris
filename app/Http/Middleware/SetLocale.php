<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale')
            ?? $request->cookie('locale')
            ?? config('app.locale', 'id');

        $supported = ['id', 'en', 'ar'];
        if (! in_array($locale, $supported, true)) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}