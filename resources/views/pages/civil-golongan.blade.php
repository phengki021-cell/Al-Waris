@extends('layouts.app')

@section('title', 'Golongan Ahli Waris Perdata')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Golongan Ahli Waris (KUHPerdata)</h1>
        <p class="mt-2 text-sm text-ink/70">Sistem empat golongan ahli waris ab intestato. Golongan yang lebih dekat mengecualikan yang lebih jauh.</p>
    </section>

    <div class="space-y-4">
        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Golongan I</h2>
            <p class="mt-1 text-sm text-ink/80">Keturunan (anak, cucu) dan pasangan yang hidup terlama. Mereka adalah ahli waris utama; golongan ini mengecualikan golongan di bawahnya.</p>
        </div>
        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Golongan II</h2>
            <p class="mt-1 text-sm text-ink/80">Orang tua, saudara (kandung/seayah/seibu), dan keturunan mereka. Berhak jika tidak ada Golongan I.</p>
        </div>
        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Golongan III</h2>
            <p class="mt-1 text-sm text-ink/80">Kakek, nenek, dan keturunan mereka (paman/bibi, sepupu). Berhak jika tidak ada Golongan I dan II.</p>
        </div>
        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Golongan IV</h2>
            <p class="mt-1 text-sm text-ink/80">Sanak saudara yang lebih jauh (keluarga sedarah). Berhak jika tidak ada Golongan I, II, dan III.</p>
        </div>
    </div>

    <div class="u-smart-card p-5">
        <h2 class="text-sm font-bold text-ink">Prinsip</h2>
        <p class="mt-1 text-sm text-ink/80">Semakin dekat hubungan keluarga, semakin berhak mewaris. Golongan yang lebih dekat mengecualikan yang lebih jauh. Urutan: I → II → III → IV.</p>
        <p class="mt-2 text-xs text-ink/50">Dasar hukum: KUHPerdata (golongan ahli waris) — perlu verifikasi teks resmi. Lihat materi lengkap di <a href="{{ route('civil.materi') }}" class="text-[#123D32] underline">Materi</a>.</p>
    </div>
</div>
@endsection