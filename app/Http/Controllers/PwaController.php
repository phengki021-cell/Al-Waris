<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Menyajikan artefak PWA (manifest + service worker) melalui rute supaya
 * dapat diuji otomatis tanpa bergantung pada web server untuk berkas statis.
 *
 * Service worker SENGAJA tidak menyimpan halaman/konten dinamis berisi data
 * pengguna (hasil kalkulasi, riwayat) — hanya aset statis & halaman offline.
 */
class PwaController extends Controller
{
    /** Keluarkan manifest web app. */
    public function manifest(): JsonResponse
    {
        return response()->json([
            'id' => '/',
            'name' => 'Al-Waris — Edukasi & Kalkulator Faraid',
            'short_name' => 'Al-Waris',
            'description' => 'Website edukasi dan simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia.',
            'lang' => 'id',
            'dir' => 'ltr',
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'theme_color' => '#064E3B',
            'background_color' => '#F8FAFC',
            'categories' => ['education'],
            'icons' => [
                [
                    'src' => '/icons/icon-192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any',
                ],
                [
                    'src' => '/icons/icon-512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any',
                ],
                [
                    'src' => '/icons/maskable-192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'maskable',
                ],
                [
                    'src' => '/icons/maskable-512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'maskable',
                ],
            ],
        ], 200, [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /** Tangani berkas service worker dengan tipe & kepala jawaban yang benar. */
    public function serviceWorker(): Response
    {
        $path = resource_path('pwa/sw.js');

        return response(file_get_contents($path), 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'no-cache',
            'Service-Worker-Allowed' => '/',
        ]);
    }
}
