@extends('layouts.app')

@section('title', 'Offline')

@section('content')
<div class="mx-auto max-w-xl text-center">
    <p class="text-5xl" aria-hidden="true">📴</p>
    <h1 class="mt-4 text-2xl font-bold md:text-3xl">Anda sedang offline</h1>
    <p class="mt-3 text-ink/70">
        Halaman yang Anda minta membutuhkan koneksi internet. Halaman offline ini
        (bersama ikon dan tampilan aplikasi) adalah satu-satunya konten yang
        tersimpan di perangkat saat koneksi terputus.
    </p>
    <p class="mt-3 text-sm text-ink/60">
        Halaman <strong>kalkulator dan riwayat sengaja tidak disimpan di cache</strong> karena
        berisi data perhitungan Anda — supaya tidak ada data pribadi yang menempel di
        perangkat lewat cache.
    </p>
    <a href="{{ route('home') }}" class="mt-6 inline-block rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700">
        Ke Halaman Utama
    </a>
</div>
@endsection
