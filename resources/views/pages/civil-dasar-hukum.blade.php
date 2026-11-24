@extends('layouts.app')

@section('title', 'Dasar Hukum & Pasal KUHPerdata')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Dasar Hukum & Pasal</h1>
        <p class="mt-2 text-sm text-ink/70">Rujukan pasal KUHPerdata (BW) yang berkaitan dengan pewarisan. Semua pasal ditandai "perlu verifikasi teks resmi".</p>
    </section>

    <div class="space-y-3">
        @php
            $pasal = [
                ['no' => '830', 'pokok' => 'Terbukanya warisan', 'ket' => 'Warisan terbuka karena kematian seseorang.'],
                ['no' => '832', 'pokok' => 'Golongan ahli waris', 'ket' => 'Empat golongan ahli waris ab intestato.'],
                ['no' => '833–834', 'pokok' => 'Bagian pasangan', 'ket' => 'Kedudukan suami/istri yang ditinggalkan.'],
                ['no' => '838', 'pokok' => 'Penggantian tempat (plaatsvervulling)', 'ket' => 'Keturunan menggantikan kedudukan orang tua yang meninggal lebih dahulu.'],
                ['no' => '840', 'pokok' => 'Ketidakpatutan mewaris (onwaardigheid)', 'ket' => 'Orang yang tidak patut dianggap tidak pernah menjadi ahli waris.'],
                ['no' => '875–912', 'pokok' => 'Wasiat (testament)', 'ket' => 'Bentuk, syarat, pencabutan, dan pelaksanaan wasiat.'],
                ['no' => '913–928', 'pokok' => 'Legitime portie', 'ket' => 'Bagian mutlak ahli waris tertentu.'],
                ['no' => '939–958', 'pokok' => 'Hibah (schenking)', 'ket' => 'Pemberian semasa hidup dan pengaruhnya terhadap warisan.'],
                ['no' => '959–975', 'pokok' => 'Penerimaan dan penolakan warisan', 'ket' => 'Murni, beneficiaire, atau tolak.'],
                ['no' => '976–1003', 'pokok' => 'Boedel, utang, kewajiban', 'ket' => 'Harta peninggalan dan utang pewaris.'],
                ['no' => '1004–1033', 'pokok' => 'Penyelesaian dan ketentuan lain', 'ket' => 'Tahapan penyelesaian waris perdata.'],
            ];
        @endphp
        @foreach ($pasal as $p)
            <div class="u-smart-card p-4">
                <div class="flex items-start gap-3">
                    <span class="u-step-badge shrink-0">Pasal {{ $p['no'] }}</span>
                    <div>
                        <p class="text-sm font-bold text-ink">{{ $p['pokok'] }}</p>
                        <p class="mt-1 text-xs text-ink/70">{{ $p['ket'] }}</p>
                        <p class="mt-1 text-[10px] text-ink/45">Status: perlu verifikasi teks resmi KUHPerdata.</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-ink/50">
        Rentang Pasal 830–1033 KUHPerdata adalah rentang penting untuk diteliti, tetapi tidak membatasi penelitian
        hanya pada rentang tersebut apabila ada peraturan lain yang relevan. Semua rujukan ditandai "perlu verifikasi".
    </p>
</div>
@endsection