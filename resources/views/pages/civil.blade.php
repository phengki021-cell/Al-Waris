@extends('layouts.app')

@section('title', 'Hukum Waris Perdata')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 pb-32">
    @include('partials.back-button')

    <section class="relative -mx-4 overflow-hidden rounded-b-[2rem] bg-gradient-to-br from-[#09271F] via-[#123D32] to-[#147F70] px-5 py-8 text-ivory md:-mx-8 md:rounded-b-[3rem] md:px-10 md:py-10">
        <p class="u-kicker text-gold">Modul Baru</p>
        <h1 class="u-display mt-1.5 text-2xl md:text-3xl">Hukum Waris Perdata</h1>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ivory/80 md:text-base">
            Pelajari sistem kewarisan berdasarkan Kitab Undang-Undang Hukum Perdata (KUHPerdata/BW).
            Modul ini disusun sebagai pelengkap edukasi Al-Waris, terpisah dari Hukum Waris Islam (faraid).
        </p>
    </section>

    {{-- Pengantar --}}
    <section class="u-smart-card p-5 md:p-6">
        <h2 class="u-display text-lg text-ink">Pengertian Hukum Waris Perdata</h2>
        <p class="mt-2 text-sm leading-relaxed text-ink/80">
            Hukum waris perdata mengatur peralihan harta peninggalan seseorang (pewaris) kepada
            orang-orang yang berhak (ahli waris) berdasarkan ketentuan KUHPerdata (BW), baik
            secara ab intestato (tanpa wasiat) maupun testamentair (dengan wasiat).
        </p>
    </section>

    {{-- Dasar hukum --}}
    <section class="u-smart-card p-5 md:p-6">
        <h2 class="u-display text-lg text-ink">Dasar Hukum</h2>
        <p class="mt-2 text-sm leading-relaxed text-ink/80">
            KUHPerdata (Burgerlijk Wetboek) Buku II, khususnya Pasal 830–1033, mengatur
            pembukaan warisan, siapa yang berhak mewaris, pembagian, wasiat, legitieme portie,
            hibah, dan penyelesaian utang pewaris.
        </p>
    </section>

    {{-- Perbedaan Islam vs Perdata --}}
    <section class="u-smart-card p-5 md:p-6">
        <h2 class="u-display text-lg text-ink">Perbedaan dengan Hukum Waris Islam</h2>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <div class="rounded-xl bg-emerald-soft/60 p-4">
                <p class="text-sm font-bold text-emerald-900">Hukum Waris Islam (Faraid)</p>
                <p class="mt-1 text-xs text-ink/70">Bagian pasti (dzawil furudh), asabah, dzawil arham, hijab, aul, radd, gharrawain — berdasarkan Al-Qur'an, Sunnah, dan KHI.</p>
            </div>
            <div class="rounded-xl bg-gold-soft/60 p-4">
                <p class="text-sm font-bold text-gold-deep">Hukum Waris Perdata (KUHPerdata)</p>
                <p class="mt-1 text-xs text-ink/70">Empat golongan ahli waris (ab intestato), wasiat, legitieme portie, hibah, harta bersama — berdasarkan KUHPerdata (BW).</p>
            </div>
        </div>
    </section>

    {{-- Tujuan --}}
    <section class="u-smart-card p-5 md:p-6">
        <h2 class="u-display text-lg text-ink">Tujuan Mempelajari</h2>
        <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-ink/80">
            <li>Memahami sistem kewarisan perdata yang berlaku di Indonesia.</li>
            <li>Membedakan waris Islam (faraid) dan waris perdata (BW).</li>
            <li>Mengetahui hak dan kewajiban ahli waris, wasiat, dan legitieme portie.</li>
            <li>Menjadi bahan edukasi, bukan pengganti nasihat hukum.</li>
        </ul>
    </section>

    {{-- Kartu navigasi fitur --}}
    <section class="mt-2">
        <p class="u-kicker text-gold-deep">Fitur Modul</p>
        <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-3">
            @foreach ([
                ['label' => 'Materi Hukum Waris Perdata', 'route' => 'civil.materi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H12v18H6.5A2.5 2.5 0 0 1 4 18.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 5.5A2.5 2.5 0 0 0 17.5 3H12v18h5.5a2.5 2.5 0 0 0 2.5-2.5Z"/>'],
                ['label' => 'Golongan Ahli Waris', 'route' => 'civil.materi', 'icon' => '<circle cx="12" cy="8" r="3.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 19.5c.6-3.2 3.4-5.25 7-5.25s6.4 2.05 7 5.25"/>'],
                ['label' => 'Dasar Hukum & Pasal', 'route' => 'civil.materi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 4h6a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 7a3 3 0 0 1 3-3h2v13h-2a3 3 0 0 0-3 3"/><path stroke-linecap="round" d="M8 8h3M8 11h3"/>'],
                ['label' => 'Kalkulator Waris Perdata', 'route' => 'civil.calculator', 'icon' => '<rect x="5" y="3" width="14" height="18" rx="2.5"/><path stroke-linecap="round" d="M9 7h6m-6 4h.01M12 11h.01M15 11h.01M9 14h.01M12 14h.01M15 14h.01M9 17h6"/>'],
                ['label' => 'Diagram Silsilah', 'route' => 'civil.materi', 'icon' => '<circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" d="M12 3.5V12l6 3.5"/>'],
                ['label' => 'Contoh Kasus', 'route' => 'civil.materi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H12v18H6.5A2.5 2.5 0 0 1 4 18.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 5.5A2.5 2.5 0 0 0 17.5 3H12v18h5.5a2.5 2.5 0 0 0 2.5-2.5Z"/>'],
                ['label' => 'Surat Wasiat', 'route' => 'civil.materi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path stroke-linecap="round" d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
                ['label' => 'Legitime Portie', 'route' => 'civil.materi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>'],
                ['label' => 'Riwayat Perhitungan', 'route' => 'history', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5v3.5H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.5A8.5 8.5 0 1 1 3.9 12"/><path stroke-linecap="round" d="M12 8v4.5l3 2"/>'],
            ] as $card)
                <a href="{{ route($card['route']) }}"
                   class="group flex flex-col items-center gap-2 rounded-2xl bg-paper p-4 text-center ring-1 ring-black/5 shadow-smooth transition hover:-translate-y-0.5 hover:ring-gold/40 active:scale-[0.97]">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200 transition group-hover:bg-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true">{!! $card['icon'] !!}</svg>
                    </span>
                    <span class="text-[0.66rem] font-semibold leading-tight text-charcoal/80">{{ $card['label'] }}</span>
                </a>
            @endforeach
        </div>
        <p class="mt-4 text-xs text-ink/50">
            Fitur yang belum dibangun pada tahap ini menampilkan halaman kerangka informatif.
            Kalkulator perdata belum menghasilkan perhitungan sampai mesin hukumnya tersedia.
        </p>
    </section>
</div>
@endsection