<?php

use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\HeirAdminController;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Admin\ShareAdminController;
use App\Http\Controllers\Admin\SourceAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
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

Route::get('/pengaturan', [SettingsController::class, 'index'])->name('settings');
Route::post('/pengaturan/bahasa', [SettingsController::class, 'setLocale'])->name('settings.locale');

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:10,1');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.store')->middleware('throttle:10,1');
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

// ===== Panel admin (kayak pengelolaan master data sifasya) =====
Route::get('/admin/login', [AdminAuth::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuth::class, 'login'])->middleware('throttle:10,1')->name('admin.attempt');
Route::post('/admin/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/ahli-waris', [HeirAdminController::class, 'index'])->name('heirs');
    Route::get('/ahli-waris/baru', [HeirAdminController::class, 'create'])->name('heirs.create');
    Route::post('/ahli-waris', [HeirAdminController::class, 'store'])->name('heirs.store');
    Route::get('/ahli-waris/{heir}/edit', [HeirAdminController::class, 'edit'])->name('heirs.edit');
    Route::put('/ahli-waris/{heir}', [HeirAdminController::class, 'update'])->name('heirs.update');
    Route::delete('/ahli-waris/{heir}', [HeirAdminController::class, 'destroy'])->name('heirs.destroy');

    // Bagian warisan (shares)
    Route::get('/bagian-warisan', [ShareAdminController::class, 'index'])->name('shares');
    Route::get('/bagian-warisan/baru', [ShareAdminController::class, 'create'])->name('shares.create');
    Route::post('/bagian-warisan', [ShareAdminController::class, 'store'])->name('shares.store');
    Route::get('/bagian-warisan/{share}/edit', [ShareAdminController::class, 'edit'])->name('shares.edit');
    Route::put('/bagian-warisan/{share}', [ShareAdminController::class, 'update'])->name('shares.update');
    Route::delete('/bagian-warisan/{share}', [ShareAdminController::class, 'destroy'])->name('shares.destroy');

    // Referensi (sources)
    Route::get('/referensi', [SourceAdminController::class, 'index'])->name('sources');
    Route::get('/referensi/baru', [SourceAdminController::class, 'create'])->name('sources.create');
    Route::post('/referensi', [SourceAdminController::class, 'store'])->name('sources.store');
    Route::get('/referensi/{source}/edit', [SourceAdminController::class, 'edit'])->name('sources.edit');
    Route::put('/referensi/{source}', [SourceAdminController::class, 'update'])->name('sources.update');
    Route::delete('/referensi/{source}', [SourceAdminController::class, 'destroy'])->name('sources.destroy');

    // Profil admin (ganti nama & kata sandi)
    Route::get('/profil', [AdminProfile::class, 'show'])->name('profile');
    Route::put('/profil/nama', [AdminProfile::class, 'updateName'])->name('profile.name');
    Route::put('/profil/sandi', [AdminProfile::class, 'updatePassword'])->name('profile.password');
});
