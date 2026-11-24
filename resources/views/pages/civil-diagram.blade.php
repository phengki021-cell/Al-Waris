@extends('layouts.app')

@section('title', 'Diagram Silsilah Keluarga')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Diagram Silsilah Keluarga</h1>
        <p class="mt-2 text-sm text-ink/70">
            Visualisasi hubungan keluarga untuk kasus Hukum Waris Perdata. Gunakan data dari Formulir Data Keluarga.
        </p>
    </section>

    <div class="u-smart-card p-5">
        <p class="text-sm text-ink/70">
            Diagram silsilah interaktif akan menampilkan pewaris, pasangan, anak, orang tua, saudara, dan keturunan
            yang telah meninggal lebih dahulu. Untuk saat ini, gunakan <a href="{{ route('civil.family') }}" class="text-[#123D32] underline">Formulir Data Keluarga</a>
            (langkah 8) untuk melihat diagram sederhana berdasarkan data yang Anda masukkan.
        </p>
        <p class="mt-3 text-xs text-ink/50">
            Diagram tidak boleh menampilkan hubungan yang salah. Penggantian tempat (plaatsvervulling) ditandai dengan
            penjelasan, bukan kesimpulan otomatis.
        </p>
    </div>
</div>
@endsection