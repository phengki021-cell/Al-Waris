<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#08152F">

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

    {{-- PWA (Tahap 4) --}}
    <link rel="manifest" href="{{ route('manifest') }}">

    {{-- Tema terang/gelap: terapkan SEBELUM paint agar tidak berkedip (FOUC). --}}
    <script>
        try {
            var stored = localStorage.getItem('al-waris-theme');
            if (stored === 'dark' || (stored === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('theme-dark');
            }
        } catch (e) {
            /* preferensi tidak tersedia — biarkan tema terang bawaan */
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
    @stack('styles')
</head>
<body class="min-h-screen bg-ivory font-sans text-charcoal antialiased">
    @php
        // Navigasi inti: nama rute => [label, path ikon SVG]
        $sidebarLinks = [
            'home'    => ['Beranda', '<path stroke-linecap="round" stroke-linejoin="round" d="M4 11.5 12 4l8 7.5M6 10v9a1 1 0 0 0 1 1h3.5v-5.5h3V20H17a1 1 0 0 0 1-1v-9"/>'],
            'heirs'   => ['Ahli Waris', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 11a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 19.5c.6-3.2 3.4-5.25 7-5.25s6.4 2.05 7 5.25"/>'],
            'shares'  => ['Bagian Warisan', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5a8.5 8.5 0 1 0 8.5 8.5H12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 3.75A8.7 8.7 0 0 1 21.25 10H15V4.25"/>'],
            'how-to'  => ['Cara Menghitung', '<path stroke-linecap="round" stroke-linejoin="round" d="M6.5 3.5h11a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1h-11a1 1 0 0 1-1-1v-15a1 1 0 0 1 1-1Z"/><path stroke-linecap="round" d="M9 8h6M9 12h.01M12 12h.01M15 12h.01M9 16h.01M12 16h3"/>'],
            'calculate' => ['Kalkulator', '<path stroke-linecap="round" stroke-linejoin="round" d="M6 3.5h12a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-15a1 1 0 0 1 1-1Z"/><path stroke-linecap="round" d="M8.5 8h7M8.5 12h.01M12 12h.01M15.5 12h.01M8.5 16h.01M12 16h.01M15.5 16h3.49"/>'],
            'history' => ['Riwayat', '<path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5v3.5H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.5A8.5 8.5 0 1 1 3.9 12"/><path stroke-linecap="round" d="M12 8v4.5l3 2"/>'],
            'sources' => ['Referensi', '<path stroke-linecap="round" stroke-linejoin="round" d="M5 4h6a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 7a3 3 0 0 1 3-3h2v13h-2a3 3 0 0 0-3 3"/><path stroke-linecap="round" d="M8 8h3M8 11h3"/>'],
            'about'   => ['Tentang', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 13.5a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.75a7.5 7.5 0 1 1 15 7.5 7.5 7.5 0 0 1-15-.75"/><path stroke-linecap="round" d="M10 20.5 12 18l2 2.5"/>'],
        ];

        // Nama rute bisa null pada halaman error (404/500) — jaga null-safety.
        $currentRoute = optional(request()->route())->getName();
        $currentLabel = ($currentRoute !== null && isset($sidebarLinks[$currentRoute]))
            ? $sidebarLinks[$currentRoute][0]
            : 'Halaman';
    @endphp

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[80] focus:bg-gold focus:px-4 focus:py-2 focus:text-deep">
        Lompat ke konten utama
    </a>

    {{-- ===== Sidebar desktop (lg+) ===== --}}
    <aside x-data
           class="fixed inset-y-0 left-0 z-50 hidden w-72 flex-col overflow-y-auto bg-deep lg:flex"
           style="background:linear-gradient(180deg, #08152F 0%, #0B1B3A 62%, #10254B 100%)"
           aria-label="Navigasi samping">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="group flex items-center gap-3 px-6 pt-7 pb-6">
            <span class="relative grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-burgundy/90 ring-1 ring-gold/50 shadow-glow">
                <svg viewBox="0 0 48 48" class="h-7 w-7" aria-hidden="true">
                    <g fill="none" stroke="#D4AF57" stroke-width="2">
                        <rect x="13" y="13" width="22" height="22"/>
                        <rect x="13" y="13" width="22" height="22" transform="rotate(45 24 24)"/>
                    </g>
                    <circle cx="24" cy="24" r="3" fill="#00CFFF"/>
                </svg>
            </span>
            <span class="leading-none">
                <span class="block text-lg font-bold uppercase tracking-[0.28em] text-ivory">Al&nbsp;Waris</span>
                <span class="mt-1.5 block text-[0.58rem] uppercase tracking-[0.3em] text-gold/80">Warisan Islam, Untuk Masa Depan</span>
            </span>
        </a>

        <div class="mx-6 h-px u-hairline opacity-50" aria-hidden="true"></div>

        {{-- Navigasi ikon + teks --}}
        <nav class="flex-1 space-y-1 px-4 py-5" aria-label="Navigasi utama">
            @foreach ($sidebarLinks as $name => [$label, $icon])
                @php $active = request()->routeIs($name); @endphp
                <a href="{{ route($name) }}"
                   class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-300
                          {{ $active
                              ? 'u-sidenav-active'
                              : 'text-ivory/70 hover:translate-x-1 hover:bg-white/5 hover:text-ivory' }}"
                   @if ($active) aria-current="page" @endif
                >
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg transition-colors duration-300
                                 {{ $active
                                     ? 'bg-burgundy text-gold ring-1 ring-gold/40'
                                     : 'bg-white/5 text-gold/70 ring-1 ring-white/10 group-hover:ring-gold/40' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-[1.15rem] w-[1.15rem]" aria-hidden="true">{!! $icon !!}</svg>
                    </span>
                    <span>{{ $label }}</span>
                    @if ($active)<span class="ml-auto h-1.5 w-1.5 rounded-full bg-gold shadow-[0_0_8px_rgba(212,175,87,0.9)]" aria-hidden="true"></span>@endif
                </a>
            @endforeach
        </nav>

        {{-- CTA kalkulator + footer mini --}}
        <div class="px-4 pb-6">
            <a href="{{ route('calculate') }}"
               class="u-btn-gold justify-center px-5 py-3 text-sm">
               <span>Hitung Warisan Sekarang</span></a>
            <p class="mt-4 px-2 text-center text-[0.62rem] leading-relaxed text-ivory/45">
                © {{ date('Y') }} Al-Waris<br>Dikembangkan oleh Hengki Pranata
            </p>
        </div>
    </aside>

    {{-- ===== Header mobile ===== --}}
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-gold/15 text-ivory shadow-lg shadow-deep/30 backdrop-blur lg:hidden" style="background:rgba(8,21,47,0.94)">
        <div class="flex h-16 items-center justify-between px-4">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-burgundy/90 ring-1 ring-gold/50">
                    <svg viewBox="0 0 48 48" class="h-5.5 w-5.5" aria-hidden="true">
                        <g fill="none" stroke="#D4AF57" stroke-width="2.4">
                            <rect x="13" y="13" width="22" height="22"/>
                            <rect x="13" y="13" width="22" height="22" transform="rotate(45 24 24)"/>
                        </g>
                    </svg>
                </span>
                <span class="text-sm font-bold uppercase tracking-[0.24em] text-ivory">Al&nbsp;Waris</span>
            </a>

            <div class="flex items-center gap-2">
                <button type="button"
                        x-data="{ dark: document.documentElement.classList.contains('theme-dark') }"
                        x-on:click="
                            dark = !dark;
                            document.documentElement.classList.toggle('theme-dark', dark);
                            try { localStorage.setItem('al-waris-theme', dark ? 'dark' : 'light'); } catch (e) {}
                        "
                        :aria-pressed="dark.toString()"
                        aria-label="Ubah tema terang atau gelap"
                        title="Ubah tema"
                        class="grid h-10 w-10 place-items-center rounded-full border border-gold/30 text-ivory transition hover:border-gold">
                    <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13.5A8.5 8.5 0 0 1 10.5 4a8.5 8.5 0 1 0 9.5 9.5Z"/></svg>
                    <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><circle cx="12" cy="12" r="4.25"/><path stroke-linecap="round" d="M12 2.75v2m0 14.5v2m9.25-9.25h-2m-14.5 0h-2m15.8-6.55-1.42 1.42M6.36 17.64l-1.42 1.42m14.12 0-1.42-1.42M6.36 6.36 4.94 4.94"/></svg>
                </button>

                <button type="button" x-on:click="open = !open" x-cloak
                        class="grid h-10 w-10 place-items-center rounded-full border border-gold/30 text-ivory transition hover:border-gold"
                        aria-label="Buka menu"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-nav">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" d="M4 7h16M4 12h10M4 17h16"/></svg>
                    <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>

        {{-- Drawer navigasi mobile --}}
        <div id="mobile-nav" x-show="open" x-cloak
             x-on:keydown.escape.window="open = false"
             x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-3"
             class="border-t border-gold/15 pb-4" style="background:#08152F">
            <nav class="grid gap-1 px-5 py-4" aria-label="Navigasi mobile">
                @foreach ($sidebarLinks as $name => [$label, $icon])
                    @php $active = request()->routeIs($name); @endphp
                    <a href="{{ route($name) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-3 text-[0.95rem] transition
                              {{ $active ? 'bg-burgundy/25 font-semibold text-gold ring-1 ring-gold/40' : 'text-ivory/85' }}"
                       x-on:click="open = false"
                       @if ($active) aria-current="page" @endif
                    >
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-white/5 text-gold/80 ring-1 ring-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-4 w-4" aria-hidden="true">{!! $icon !!}</svg>
                        </span>
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('calculate') }}" class="u-btn-gold mt-3 justify-center py-3" x-on:click="open = false"><span>Hitung Warisan Sekarang</span></a>
            </nav>
        </div>
    </header>

    {{-- ===== Shell konten ===== --}}
    <div class="lg:pl-72">
        {{-- Header breadcrumb desktop --}}
        <div class="sticky top-0 z-30 hidden border-b border-charcoal/8 bg-ivory/85 backdrop-blur lg:block">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-8">
                <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
                    <a href="{{ route('home') }}" class="text-charcoal/55 transition hover:text-burgundy">Beranda</a>
                    @if ($currentRoute !== 'home')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-3.5 w-3.5 text-charcoal/30" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6"/></svg>
                        <span class="font-semibold text-burgundy" aria-current="page">{{ $currentLabel }}</span>
                    @endif
                </nav>

                <div class="flex items-center gap-3">
                    <button type="button"
                            x-data="{ dark: document.documentElement.classList.contains('theme-dark') }"
                            x-on:click="
                                dark = !dark;
                                document.documentElement.classList.toggle('theme-dark', dark);
                                try { localStorage.setItem('al-waris-theme', dark ? 'dark' : 'light'); } catch (e) {}
                            "
                            :aria-pressed="dark.toString()"
                            aria-label="Ubah tema terang atau gelap"
                            title="Ubah tema"
                            class="grid h-9 w-9 place-items-center rounded-lg border border-charcoal/15 text-charcoal/70 transition hover:border-gold hover:text-burgundy"
                        >
                        {{-- Ikon bulan (tujuan gelap) tampil saat terang; ikon matahari saat gelap --}}
                        <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13.5A8.5 8.5 0 0 1 10.5 4a8.5 8.5 0 1 0 9.5 9.5Z"/></svg>
                        <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><circle cx="12" cy="12" r="4.25"/><path stroke-linecap="round" d="M12 2.75v2m0 14.5v2m9.25-9.25h-2m-14.5 0h-2m15.8-6.55-1.42 1.42M6.36 17.64l-1.42 1.42m14.12 0-1.42-1.42M6.36 6.36 4.94 4.94"/></svg>
                    </button>
                    <a href="{{ route('sources') }}" class="text-sm text-charcoal/60 transition hover:text-burgundy">Referensi</a>
                    <a href="{{ route('install') }}" class="text-sm text-charcoal/60 transition hover:text-burgundy">Pasang Aplikasi</a>
                    <a href="{{ route('calculate') }}" class="u-btn-gold px-4 py-2 text-xs"><span>Hitung Warisan</span></a>
                </div>
            </div>
        </div>

        {{-- ===== Konten utama ===== --}}
        <main id="main" class="mx-auto max-w-7xl px-4 pb-32 pt-8 md:px-8 md:pb-12 md:pt-10 lg:pb-16">
            {{ $slot ?? $content ?? '' }}
            @yield('content')
        </main>

        {{-- ===== Footer ===== --}}
        <footer class="u-lattice bg-deep text-ivory/80">
            <div class="u-hairline opacity-60"></div>
            <div class="mx-auto max-w-7xl px-4 py-12 md:px-8 md:grid md:grid-cols-3 md:gap-10">
                <div>
                    <p class="flex items-center gap-2 text-lg font-semibold uppercase tracking-[0.3em] text-ivory">Al&nbsp;Waris</p>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed">Edukasi & simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia — presisi pecahan, bersumber pijakan.</p>
                    <p class="mt-4 flex items-center gap-2 text-sm">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-gold/10 ring-1 ring-gold/40" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#D4AF57" stroke-width="1.7" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
                        </span>
                        <span>Dikembangkan oleh <a href="{{ route('about') }}" class="font-semibold text-gold transition hover:text-gold/80">Hengki Pranata</a></span>
                    </p>
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
                <p class="text-ivory/60">© {{ date('Y') }} Al-Waris | Dikembangkan oleh <span class="font-semibold text-gold/90">Hengki Pranata</span></p>
                <p class="mt-1 italic opacity-80">Al Waris — Warisan Islam, Untuk Masa Depan</p>
            </div>
        </footer>
    </div>

    {{-- ===== Navigasi bawah mobile (fitur utama) ===== --}}
    <nav aria-label="Navigasi cepat" class="fixed inset-x-0 bottom-0 z-40 border-t border-gold/20 md:hidden" style="background:rgba(8,21,47,0.96); backdrop-filter:blur(12px)">
        <div class="mx-auto grid max-w-lg grid-cols-5 px-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] pt-1.5">
            @php
                $bottomLinks = ['home', 'heirs', 'how-to', 'calculate', 'history'];
            @endphp
            @foreach ($bottomLinks as $name)
                @php
                    [$label, $icon] = $sidebarLinks[$name];
                    $active = request()->routeIs($name);
                @endphp
                <a href="{{ route($name) }}"
                   class="group relative flex flex-col items-center gap-0.5 rounded-xl px-1 py-1.5 text-[0.6rem] font-semibold transition-colors duration-300
                          {{ $active ? 'text-gold' : 'text-ivory/60' }}"
                   @if ($active) aria-current="page" @endif
                >
                    @if ($active)
                        <span class="absolute -top-1.5 h-1 w-8 rounded-full bg-burgundy shadow-[0_0_10px_rgba(142,23,68,0.9)]" aria-hidden="true"></span>
                    @endif
                    <span class="grid h-8 w-8 place-items-center rounded-lg transition-colors duration-300 {{ $active ? 'bg-burgundy/25 text-gold' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.15rem] w-[1.15rem]" aria-hidden="true">{!! $icon !!}</svg>
                    </span>
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- ===== Toast ajakan instalasi PWA — mengambang kanan bawah, sekali per kunjungan pertama (dijalankan resources/js/app.js) ===== --}}
    <div
        id="pwa-install-popup"
        hidden
        class="fixed bottom-24 right-4 z-[70] w-[calc(100vw-2rem)] max-w-sm md:bottom-4"
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
                        <g fill="none" stroke="#D4AF57" stroke-width="1.8">
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
