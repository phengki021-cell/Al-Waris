@extends('layouts.app')

@section('title', 'Cara Menghitung')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Cara Menghitung Warisan</h1>
    <p class="mt-2 text-sm text-ink/70 max-w-3xl">
        Ilmu faraid memiliki beberapa metode untuk menyelesaikan masalah bagi-bagi harta. Halaman ini menyediakan
        kerangka kerja, sedangkan konten penuh lengkap dengan diagram proses adalah pekerjaan Tahap 2–4.
    </p>
</section>

<section class="mt-6 grid gap-4 md:grid-cols-2">
    @foreach ([
        ['title' => 'Amal Ummi (perencanaan umum)', 'desc' => 'Menyamakan penyebut (angka dasar/asl) seluruh bagian agar perhitungan berjalan dengan pecahan yang setara.','status' => 'planned' ,'label' => 'Tahap 2'],
        ['title' => 'A\'ul', 'desc' => 'Ketika total pecahan bagian melebihi satu — diatasi dengan menaikkan angka dasar. Contoh klasik: istri 1/8, saudara sedarah perempuan 2/3, saudara sedarah laki-laki — asal 24 dinaikkan menjadi 27.','status' => 'planned', 'label' => 'Tahap 2'],
        ['title' => 'Radd', 'desc' => 'Kebalikan \'aul: sisa harta yang tidak habis terbagi dikembalikan kepada ahli waris tertentu (tidak pada pasangan dan orang tua).','status' => 'planned', 'label' => 'Tahap 2'],
        ['title' => 'Gawalan', 'desc' => 'Kasus kedudukan ahli waris yang tidak saling jelas (salih/terhalang) — dibahas beserta kaidah klasiknya.','status' => 'planned', 'label' => 'Tahap 3'],
        ['title' => 'Hisab Asl & Sihām', 'desc' => 'Alur menghitung angka dasar (asl) untuk keluarga kompleks: penghalang (mungani), pembagian siham, dan duinya mendekati.','status' => 'planned', 'label' => 'Tahap 2'],
        ['title' => 'Wasiat, Wakaf & Hibah', 'desc' => 'Hubungan faraid dengan pembagian non-pewaris: batas wasiat 1/3, wakaf, dan hibah.','status' => 'planned', 'label' => 'Tahap 3–4'],
    ] as $card)
        <article class="rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5 {{ $card['status'] === 'planned' ? 'opacity-70' : '' }}">
            <h2 class="font-semibold text-primary">{{ $card['title'] }}</h2>
            <p class="mt-2 text-sm text-ink/80">{{ $card['desc'] }}</p>
            <p class="mt-3 inline-block rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">{{ $card['label'] }}</p>
        </article>
    @endforeach
</section>

<section class="mt-8 rounded-xl bg-primary p-6 text-white">
    <h2 class="text-lg font-bold">Belajar dengan kalkulator</h2>
    <p class="mt-2 text-sm text-white/85">
        Mesin hitung penuh hadir pada Tahap 2. Pada Tahap 1, halaman <a href="{{ route('calculate') }}" class="text-accent underline">Hitung Warisan</a>
        sudah menampilkan mesin pratinjau untuk beberapa kasus dasar dengan pecahan eksak.
    </p>
</section>
@endsection
