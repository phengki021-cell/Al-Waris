<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#123D32">

    <title>@if (Route::currentRouteName() !== 'home') {{ $title ?? 'Al-Waris' }} — @endif Al-Waris</title>
    <meta name="description" content="{{ $description ?? 'Al-Waris: website edukasi & kalkulator pembagian harta warisan Islam (faraid) berbahasa Indonesia.' }}">

    {{-- Open Graph dasar --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Al-Waris">
    <meta property="og:title" content="{{ $title ?? 'Al-Waris — Edukasi & Kalkulator Faraid' }}">
    <meta property="og:description" content="{{ $description ?? 'Website edukasi dan simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('/icons/icon-512.png') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Ikon --}}
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/icons/favicon-48.png" type="image/png" sizes="48x48">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon-180.png">

    <meta name="theme-color" content="#123D32">

    {{-- PWA (Tahap 4) --}}
    <link rel="manifest" href="{{ route('manifest') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>
<body class="min-h-screen bg-surface font-sans text-ink antialiased">
    @php $navLinks = [
        'home' => 'Beranda',
        'heirs' => 'Ahli Waris',
        'shares' => 'Bagian-Bagian Warisan',
        'how-to' => 'Cara Menghitung',
        'calculate' => 'Hitung Warisan',
        'history' => 'Riwayat',
        'about' => 'Tentang',
        'sources' => 'Referensi',
    ]; @endphp
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-primary focus:px-4 focus:py-2 focus:text-white">
        Lompat ke konten utama
    </a>

    {{-- ===== Header ===== --}}
    <header class="bg-primary text-white shadow">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 py-1">
                <img src="/icons/icon-192.png" alt="Logo Al-Waris" class="h-9 w-9 rounded-lg object-cover shadow-sm">
                <span class="text-lg font-bold tracking-wide">Al-Waris</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                @foreach ($navLinks as $name => $label)
                    <a href="{{ route($name) }}"
                       class="rounded-md px-3 py-2 text-sm font-medium transition hover:bg-primary-700
                              {{ request()->routeIs($name) ? 'bg-primary-700 text-accent' : 'text-white/90' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            {{-- Mobile toggle (Alpine.js) --}}
            <button x-data="{ open: false }" @click="document.getElementById('mobile-nav').classList.toggle('hidden')"
                    class="rounded-md p-2 text-white hover:bg-primary-700 md:hidden" aria-label="Buka menu" aria-expanded="false">
                ⨠ MENU
            </button>
        </div>

        {{-- Mobile nav --}}
        <nav id="mobile-nav" class="hidden border-t border-white/10 md:hidden" aria-label="Navigasi mobile">
            @foreach ($navLinks as $name => $label)
                <a href="{{ route($name) }}"
                   class="block border-b border-white/5 px-4 py-3 text-sm {{ request()->routeIs($name) ? 'bg-primary-700 text-accent' : 'text-white/90' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </header>

    {{-- ===== Konten utama ===== --}}
    <main id="main" class="mx-auto max-w-6xl px-4 py-6 md:py-10">
        {{ $slot ?? $content ?? '' }}
        @yield('content')
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="mt-10 bg-primary text-white/80">
        <div class="mx-auto max-w-6xl px-4 py-8 md:grid md:grid-cols-3 md:gap-8">
            <div>
                <p class="text-lg font-bold text-white">Al-Waris</p>
                <p class="mt-2 text-sm">Edukasi & simulasi pembagian harta warisan Islam (faraid) berbahasa Indonesia.</p>
            </div>
            <div class="mt-6 text-sm md:mt-0">
                <p class="font-semibold text-accent">Halaman</p>
                <ul class="mt-2 space-y-1">
                    <li><a href="{{ route('about') }}" class="hover:text-white">Tentang</a></li>
                    <li><a href="{{ route('install') }}" class="hover:text-white">Pasang di Perangkat</a></li>
                    <li><a href="{{ route('sources') }}" class="hover:text-white">Sumber/Referensi</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="mt-6 text-sm md:mt-0">
                <p class="font-semibold text-accent">Disclaimer</p>
                <p class="mt-2">Al-Waris adalah alat bantu edukasi dan simulasi — bukan pengganti penilaian ahli faraid atau nasihat hukum untuk sengketa nyata.</p>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-white/60">
            © {{ date('Y') }} Al-Waris — belajar tahap demi tahap.
        </div>
    </footer>

    <livewire:scripts />
    @stack('scripts')
</body>
</html>
