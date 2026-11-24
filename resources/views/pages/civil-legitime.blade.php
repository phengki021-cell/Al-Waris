@extends('layouts.app')

@section('title', 'Legitime Portie')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Legitime Portie (Bagian Mutlak)</h1>
        <p class="mt-2 text-sm text-ink/70">Pengertian, ahli waris legitimaris, ketentuan, dan contoh kasus. Dasar hukum perlu verifikasi.</p>
    </section>

    <div class="u-smart-card p-5 space-y-3">
        <h2 class="text-sm font-bold text-ink">Pengertian</h2>
        <p class="text-sm text-ink/80">Legitime portie adalah bagian mutlak yang dilindungi undang-undang bagi ahli waris tertentu, sehingga pewaris tidak dapat mencabut atau menguranginya melalui wasiat atau hibah.</p>
        <h2 class="text-sm font-bold text-ink mt-3">Ahli waris legitimaris</h2>
        <p class="text-sm text-ink/80">Biasanya anak dan pasangan. Siapa yang berhak dan persentasenya bergantung pada susunan keluarga dan ketentuan yang berlaku.</p>
        <h2 class="text-sm font-bold text-ink mt-3">Pengaruh hibah dan wasiat</h2>
        <p class="text-sm text-ink/80">Wasiat dan hibah yang melebihi batas legitime portie dapat dikurangi (reductie). Tidak ada satu persentase yang berlaku untuk semua.</p>
        <h2 class="text-sm font-bold text-ink mt-3">Contoh kasus</h2>
        <p class="text-sm text-ink/80">Pewaris membuat wasiat yang diduga melanggar bagian mutlak anak. Jika legitime portie dilanggar, ahli waris yang dirugikan dapat menuntut pengurangan.</p>
        <p class="mt-3 text-xs text-ink/50">Sumber: KUHPerdata Pasal 913–928 — perlu verifikasi teks resmi. Persentase dan dasar penghitungan harus diverifikasi.</p>
    </div>
</div>
@endsection