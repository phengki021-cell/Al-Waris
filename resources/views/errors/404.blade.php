@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="mx-auto max-w-xl text-center">
    <p class="text-6xl font-black text-primary/15">404</p>
    <h1 class="mt-2 text-2xl font-bold md:text-3xl">Halaman tidak ditemukan</h1>
    <p class="mt-3 text-ink/70">
        Halaman atau tautan yang Anda buka tidak ada, atau sudah dipindah.
    </p>

    <div class="mt-6 grid gap-2 text-sm sm:grid-cols-2">
        <a href="{{ route('home') }}" class="rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary-700">
            Ke Beranda
        </a>
        <a href="{{ route('calculate') }}" class="rounded-lg border border-ink/20 px-4 py-3 font-semibold hover:bg-paper">
            Hitung Warisan
        </a>
        <a href="{{ route('heirs') }}" class="rounded-lg border border-ink/20 px-4 py-3 font-semibold hover:bg-paper">
            Materi Ahli Waris
        </a>
        <a href="{{ route('sources') }}" class="rounded-lg border border-ink/20 px-4 py-3 font-semibold hover:bg-paper">
            Referensi
        </a>
    </div>
</div>
@endsection
