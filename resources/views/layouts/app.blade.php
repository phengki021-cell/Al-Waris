<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#064E3B">

    <title>@if (Route::currentRouteName() !== 'home' && ($pageTitle = $title ?? trim($__env->yieldContent('title')))){{ trim($pageTitle) . ' — ' }}@endif{{ config('app.name', 'Al-Waris') }}</title>
    <meta name="description" content="{{ $description ?? 'Al-Waris: website edukasi & kalkulator pembagian harta warisan Islam (faraid) berbahasa Indonesia.' }}">

    {{-- Open Graph dasar --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name', 'Al-Waris') }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'Al-Waris') . ' — Edukasi & Kalkulator Faraid' }}">
    <meta property="og:description" content="{{ $description ?? 'Website edukasi dan simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('/icons/icon-512.png') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Ikon --}}
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/icons/favicon-48.png" type="image/png" sizes="48x48">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon-180.png">

    <meta name="theme-color" content="#064E3B">

    {{-- PWA (Tahap 4) --}}
    <link rel="manifest" href="{{ route('manifest') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
    @stack('styles')
</head>
<body class="min-h-screen bg-ivory font-sans text-charcoal antialiased">
    @php
        $navLinks = [
            'home' => 'Beranda',
            'heirs' => 'Ahli Waris',
            'shares' => 'Bagian Warisan',
            'how-to' => 'Cara Menghitung',
            'history' => 'Riwayat',
            'sources' => 'Referensi',
            'about' => 'Tentang',
        ];
    @endphp

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-[60] focus:bg-gold focus:px-4 focus:py-2 focus:text-deep">
        Lompat ke konten utama
    </a>

    {{-- ===== Header ===== --}}
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-gold/20 bg-deep/95 text-ivory shadow-lg shadow-deep/20 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 md:h-[4.5rem] md:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                {{-- Monogram bintang 8 titik --}}
                <span class="relative grid h-10 w-10 place-items-center">
                    <svg viewBox="0 0 48 48" class="h-10 w-10" aria-hidden="true">
                        <g fill="none" stroke="#F59E0B" stroke-width="1.6">
                            <rect x="12" y="12" width="24" height="24"/>
                            <rect x="12" y="12" width="24" height="24" transform="rotate(45 24 24)"/>
                            <circle cx="24" cy="24" r="8.5"/>
                        </g>
                        <circle cx="24" cy="24" r="2.4" fill="#F59E0B"/>
                    </svg>
                    <span class="absolute inset-0 -m-1 rounded-full bg-gold/0 transition group-hover:bg-gold/10"></span>
                </span>
                <span class="leading-none">
                    <span class="block text-base font-semibold uppercase tracking-[0.3em] text-ivory">Al&nbsp;Waris</span>
                    <span class="mt-1 block text-[0.6rem] uppercase tracking-[0.34em] text-gold/80">Faraid · Legal&nbsp;Tech</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-5 lg:flex" aria-label="Navigasi utama">
                @foreach ($navLinks as $name => $label)
                    <a href="{{ route($name) }}"
                       class="u-navlink"
                       @if (request()->routeIs($name)) aria-current="page" @endif
                    >{{ $label }}</a>
                @endforeach
                <a href="{{ route('calculate') }}"
                   class="u-btn-gold px-5 py-2.5 text-sm"
                   @if (request()->routeIs('calculate')) aria-current="page" @endif
                ><span>Hitung Warisan</span></a>
            </nav>

            {{-- Mobile toggle --}}
            <button type="button" x-on:click="open = !open" x-cloak
                    class="grid h-10 w-10 place-items-center rounded-full border border-gold/30 text-ivory transition hover:border-gold lg:hidden"
                    aria-label="Buka menu"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-nav">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" d="M4 7h16M4 12h10M4 17h16"/></svg>
                <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>

        {{-- Mobile drawer --}}
        <div id="mobile-nav" x-show="open" x-cloak
             x-on:keydown.escape.window="open = false"
             x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-3"
             class="u-lattice border-t border-gold/15 lg:hidden" style="background:#064E3B">
            <nav class="mx-auto grid max-w-7xl gap-1 px-5 py-6" aria-label="Navigasi mobile">
                @foreach ($navLinks as $name => $label)
                    <a href="{{ route($name) }}"
                       class="border-b border-white/5 py-3 text-lg {{ request()->routeIs($name) ? 'text-gold' : 'text-ivory/85' }}"
                       x-on:click="open = false"
                    >{{ $label }}</a>
                @endforeach
                <a href="{{ route('calculate') }}" class="u-btn-gold mt-4 justify-center py-3" x-on:click="open = false"><span>Hitung Warisan</span></a>
            </nav>
        </div>
    </header>

    {{-- ===== Konten utama ===== --}}
    <main id="main" class="mx-auto max-w-7xl px-4 py-8 md:px-8 md:py-12">
        {{ $slot ?? $content ?? '' }}
        @yield('content')
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="u-lattice mt-4 bg-deep text-ivory/80">
        <div class="u-hairline opacity-60"></div>
        <div class="mx-auto max-w-7xl px-4 py-12 md:px-8 md:grid md:grid-cols-3 md:gap-10">
            <div>
                <p class="flex items-center gap-2 text-lg font-semibold uppercase tracking-[0.3em] text-ivory">Al&nbsp;Waris</p>
                <p class="mt-3 max-w-sm text-sm leading-relaxed">Edukasi &amp; simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia — presisi pecahan, bersumber pijakan.</p>
            </div>
            <div class="mt-8 text-sm md:mt-0">
                <p class="u-kicker text-gold">Halaman</p>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('about') }}" class="transition hover:text-gold">Tentang</a></li>
                    <li><a href="{{ route('install') }}" class="transition hover:text-gold">Pasang di Perangkat</a></li>
                    <li><a href="{{ route('sources') }}" class="transition hover:text-gold">Sumber/Referensi</a></li>
                    <li><a href="{{ route('privacy') }}" class="transition hover:text-gold">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="mt-8 text-sm md:mt-0">
                <p class="u-kicker text-gold">Disclaimer</p>
                <p class="mt-4 leading-relaxed">Al-Waris adalah alat bantu edukasi dan simulasi — bukan pengganti penilaian ahli faraid atau nasihat hukum untuk sengketa nyata.</p>
            </div>
        </div>
        <div class="border-t border-white/5 py-5 text-center text-xs text-ivory/50">
            © {{ date('Y') }} Al-Waris — belajar tahap demi tahap.
        </div>
    </footer>

    {{-- ===== Toast ajakan instalasi PWA — mengambang kanan bawah, sekali per kunjungan pertama (dijalankan resources/js/app.js) ===== --}}
    <div
        id="pwa-install-popup"
        hidden
        class="fixed bottom-4 right-4 z-[70] w-[calc(100vw-2rem)] max-w-sm"
        role="dialog"
        aria-labelledby="pwa-popup-title"
        aria-describedby="pwa-popup-desc"
        data-testid="pwa-install-popup"
    >
        <div class="relative overflow-hidden rounded-2xl bg-deep p-4 text-ivory shadow-2xl ring-1 ring-gold/40">
            <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-gold to-transparent" aria-hidden="true"></div>
            <button type="button" data-pwa-popup-close class="absolute right-2.5 top-2.5 grid h-8 w-8 place-items-center rounded-full bg-white/10 text-ivory/80 ring-1 ring-white/20 transition hover:bg-gold/25 hover:text-white" aria-label="Tutup popup instalasi">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
            <div class="flex items-start gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gold/15 ring-1 ring-gold/30">
                    <svg viewBox="0 0 48 48" class="h-7 w-7" aria-hidden="true">
                        <g fill="none" stroke="#FBBF24" stroke-width="1.8">
                            <rect x="13" y="13" width="22" height="22"/>
                            <rect x="13" y="13" width="22" height="22" transform="rotate(45 24 24)"/>
                            <circle cx="24" cy="24" r="7.5"/>
                        </g>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 id="pwa-popup-title" class="text-sm font-bold leading-snug">Pasang Al-Waris di Perangkat Anda</h2>
                    <p id="pwa-popup-desc" class="mt-1.5 text-xs leading-relaxed text-ivory/75">
                        Tambahkan Al-Waris ke layar depan (home screen) sebagai aplikasi: tampilan penuh,
                        buka kembali tanpa mencari tab, dan hasil perhitungan tetap tersimpan secara lokal
                        pada perangkat Anda.
                    </p>
                </div>
            </div>
            <div class="mt-3.5 flex gap-2">
                <button type="button" id="pwa-popup-accept" class="u-btn-gold flex-1 justify-center px-4 py-2.5 text-sm">
                    Instal Sekarang
                </button>
                <button type="button" id="pwa-popup-later" class="rounded-full border border-ivory/25 px-4 py-2.5 text-sm text-ivory/90 transition hover:bg-white/10">
                    Nanti Saja
                </button>
            </div>
            <p class="mt-3 text-center text-[10px] text-ivory/50">
                Di luar popup bawaan peramban? Ikuti langkah manual di halaman
                <a href="{{ route('install') }}" class="font-semibold text-gold underline">Pasang di Perangkat</a>.
            </p>
        </div>
    </div>

    <livewire:scripts />
    @stack('scripts')
</body>
</html>
