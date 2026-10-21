<?php

use App\Http\Controllers\HeirCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShareCatalogController;
use App\Http\Controllers\SourceController;
use App\Livewire\CalculationHistory;
use App\Livewire\Calculator;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', HomeController::class)->name('home');

// Edukasi
Route::get('/ahli-waris', HeirCatalogController::class)->name('heirs');
Route::get('/bagian-warisan', ShareCatalogController::class)->name('shares');
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
