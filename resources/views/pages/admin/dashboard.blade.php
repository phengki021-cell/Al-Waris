@extends('layouts.app')

@section('title', 'Admin — Dashboard')

@section('content')
<div class="flex items-center justify-between gap-3">
    <div>
        <p class="u-kicker text-gold-deep">Panel Admin</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Dashboard</h1>
        <p class="mt-1 text-sm text-ink/70">Halo, {{ auth()->user()->name ?? 'Admin' }} — ringkasan data Al-Waris.</p>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="rounded-xl border border-gold/40 bg-gold-soft/50 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold hover:text-white">
            Keluar
        </button>
    </form>
</div>

<div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
    @foreach ([
        'Ahli Waris' => [$heirsCount, route('admin.heirs')],
        'Bagian Warisan' => [$sharesCount, route('shares')],
        'Referensi' => [$sourcesCount, route('sources')],
        'Riwayat Hitung' => [$calculationsCount, route('history')],
        'Pengguna' => [$usersCount, '#'],
    ] as $label => [$count, $href])
        <a href="{{ $href }}" class="u-smart-card p-5">
            <p class="text-xs font-bold uppercase tracking-widest text-ink/50">{{ $label }}</p>
            <p class="u-display mt-2 text-3xl text-ink">{{ $count }}</p>
        </a>
    @endforeach
</div>

<section class="u-smart-card mt-6 p-5 md:p-6">
    <h2 class="text-lg font-bold">Pintasan Kelola</h2>
    <div class="mt-3 flex flex-wrap gap-3">
        <a href="{{ route('admin.heirs') }}" class="u-btn-gold px-4 py-2 text-sm">Kelola Ahli Waris</a>
        <a href="{{ route('admin.shares') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">Kelola Bagian Warisan</a>
        <a href="{{ route('admin.sources') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">Kelola Referensi</a>
        <a href="{{ route('admin.profile') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">Profil &amp; Ganti Sandi</a>
        <a href="{{ route('heirs') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">Lihat Halaman Publik</a>
    </div>
</section>
@endsection
