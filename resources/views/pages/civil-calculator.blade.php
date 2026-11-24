@extends('layouts.app')

@section('title', 'Kalkulator Waris Perdata')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Kalkulator Hukum Waris Perdata</h1>
        <p class="mt-2 text-sm text-ink/70">
            Simulasikan pembagian harta peninggalan berdasarkan ketentuan KUHPerdata.
            Antarmuka ini adalah kerangka (Tahap 2); mesin perhitungan belum tersedia.
        </p>
    </section>

    @php
        $steps = [
            'Data pewaris',
            'Harta peninggalan',
            'Pasangan dan anak',
            'Orang tua dan saudara',
            'Kakek dan nenek',
            'Paman dan bibi',
            'Keturunan ahli waris',
            'Wasiat dan hibah',
            'Pemeriksaan khusus',
        ];
    @endphp

    <div class="space-y-3">
        @foreach ($steps as $i => $step)
            <div class="u-smart-card flex items-center gap-3 p-4">
                <span class="u-step-badge">{{ $i + 1 }}</span>
                <p class="text-sm font-semibold text-ink">{{ $step }}</p>
                <span class="ml-auto text-xs text-ink/40">Belum tersedia</span>
            </div>
        @endforeach
    </div>

    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
        <strong>Catatan:</strong> Kalkulator ini belum menghasilkan perhitungan. Mesin hukum perdata
        akan dibangun pada tahap berikutnya. Jangan gunakan nominal yang ditampilkan sebagai hasil final.
    </div>
</div>
@endsection