@extends('layouts.app')

@section('title', 'Sumber dan Referensi')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Sumber / Referensi</h1>
    <p class="mt-2 text-sm text-ink/70 max-w-3xl">
        Daftar sumber yang menjadi basis aturan faraid di Al-Waris. Setiap bagian warisan dapat ditautkan ke
        sumbernya di halaman Bagian. Detail penentuan pendapat klasik akan dikategorisasi di Tahap 2.
    </p>
</section>

<section class="mt-6">
    @foreach ($sources as $source)
        <article class="mb-3 rounded-xl bg-paper p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-semibold">{{ $source->title }}</h2>
                    @if ($source->reference)
                        <p class="text-xs text-ink/60">{{ $source->reference }}</p>
                    @endif
                    @if ($source->excerpt)
                        <p class="mt-2 text-sm text-ink/80">{{ $source->excerpt }}</p>
                    @endif
                    @if ($source->url)
                        <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block text-xs text-accent underline">
                            Lihat sumber asal
                        </a>
                    @endif
                </div>
                <div class="shrink-0 text-right">
                    <span class="rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">{{ ucfirst($source->type) }}</span>
                    @if ($source->is_primary)
                        <p class="mt-1 text-[10px] text-primary/70">Sumber utama</p>
                    @endif
                </div>
            </div>
        </article>
    @endforeach
</section>
@endsection
