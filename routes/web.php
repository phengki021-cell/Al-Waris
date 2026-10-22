<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\SourceController;
use App\Livewire\CalculationHistory;
use App\Livewire\Calculator;
use App\Livewire\HeirExplorer;
use App\Livewire\ShareExplorer;
use Illuminate\Support\Facades\Route;

// PWA: manifest & service worker via rute agar uji otomatis mampu (Tahap 4).
Route::get('/manifest.json', [PwaController::class, 'manifest'])->name('manifest');
Route::get('/sw.js', [PwaController::class, 'serviceWorker'])->name('sw');

// Fallback offline (target service worker saat jaringan tiada).
Route::view('/offline', 'pages.offline')->name('offline');

// Panduan instalasi PWA.
Route::view('/instalasi', 'pages.install')->name('install');

// Halaman utama
Route::get('/', HomeController::class)->name('home');

// Edukasi (Livewire full-page: kartu dapat diklik menampilkan modal detail)
Route::get('/ahli-waris', HeirExplorer::class)->name('heirs');
Route::get('/bagian-warisan', ShareExplorer::class)->name('shares');
Route::get('/cara-menghitung', [PageController::class, 'howTo'])->name('how-to');

// Kalkulator (Livewire)
Route::get('/hitung-warisan', Calculator::class)->name('calculate');

// Riwayat (Livewire; empty state di Tahap 1)
Route::get('/riwayat', CalculationHistory::class)->name('history');

// Halaman statis
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');

// Referensi
Route::get('/referensi', SourceController::class)->name('sources');
