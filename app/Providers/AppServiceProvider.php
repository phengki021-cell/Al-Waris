<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Perlindungan mutasi: batasi laju endpoint pembaruan Livewire
        // (termasuk operasi hitung, simpan, dan hapus riwayat) maksimal
        // 60 request per menit per pengguna (tanpa akun: berdasarkan IP).
        RateLimiter::for('livewire', function (Request $request) {
            return [
                Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()),
            ];
        });

        // Terapkan lima batas laju tersebut pada rute update Livewire.
        Livewire::setUpdateRoute(function ($handle, $path) {
            return Route::post($path, $handle)
                ->middleware(['web', 'throttle:livewire']);
        });
    }
}
