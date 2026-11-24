@extends('layouts.app')

@section('title', 'Materi Hukum Waris Perdata')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Materi Hukum Waris Perdata</h1>
        <p class="mt-2 text-sm text-ink/70">
            Kerangka materi (Tahap 2). Konten hukum akan dilengkapi dan diverifikasi pada tahap berikutnya.
        </p>
    </section>

    @php
        $categories = [
            'Pengantar Hukum Waris Perdata',
            'Asas Hukum Waris Perdata',
            'Syarat Pewarisan',
            'Golongan I',
            'Golongan II',
            'Golongan III',
            'Golongan IV',
            'Penggantian Tempat',
            'Pewarisan Berdasarkan Wasiat',
            'Legitime Portie',
            'Hibah dan Warisan',
            'Harta Bersama dan Harta Pribadi',
            'Penerimaan dan Penolakan Warisan',
            'Utang dan Kewajiban Pewaris',
            'Penyelesaian Sengketa Waris',
        ];
    @endphp

    <div class="grid gap-3 md:grid-cols-2">
        @foreach ($categories as $cat)
            <div class="u-smart-card p-4">
                <h3 class="text-sm font-bold text-ink">{{ $cat }}</h3>
                <p class="mt-2 text-xs text-ink/60">Kerangka: pengertian, dasar hukum, penjelasan, contoh, rujukan pasal. (Belum tersedia)</p>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-ink/50">
        Konten hukum yang belum diverifikasi tidak ditampilkan sebagai fakta final.
    </p>
</div>
@endsection