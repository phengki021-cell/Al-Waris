@extends('layouts.app')

@section('title', 'Terjadi Kesalahan Server')

@section('content')
<div class="mx-auto max-w-xl text-center">
    <p class="text-6xl font-black text-primary/15">500</p>
    <h1 class="mt-2 text-2xl font-bold md:text-3xl">Maaf, terjadi kesalahan pada server</h1>
    <p class="mt-3 text-ink/70">
        Kegagalan internal aplikasi. Rincian teknis tidak ditampilkan demi keamanan.
        Silakan coba lagi — data formulir di kalkulator tetap tersimpan di perangkat Anda.
    </p>

    <div class="mt-6 grid gap-2 text-sm sm:grid-cols-2">
        <a href="{{ route('home') }}" class="rounded-lg bg-primary px-4 py-3 font-semibold text-white hover:bg-primary-700">
            Ke Beranda
        </a>
        <a href="{{ route('calculate') }}" class="rounded-lg border border-ink/20 px-4 py-3 font-semibold hover:bg-paper">
            Coba Kalkulator Lagi
        </a>
    </div>
</div>
@endsection
