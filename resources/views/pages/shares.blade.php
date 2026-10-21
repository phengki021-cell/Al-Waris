@extends('layouts.app')

@section('title', 'Bagian-Bagian Warisan')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Bagian-Bagian Warisan</h1>
    <p class="mt-2 text-sm text-ink/70">
        Bagian pasti (dzawil furudh) dari QS. An-Nisaa &amp; KHI. Data berasal dari tabel <code>shares</code> +
        <code>sources</code> — sumber tetap eksak. Setiap baris mencantumkan sumber.
    </p>
</section>

<section class="mt-6">
    @forelse ($shares as $share)
        <article class="mb-2 overflow-hidden rounded-xl bg-paper p-4 shadow-sm ring-1 ring-black/5 md:flex md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold">
                    {{ $share->heir->name_id }}
                    <span class="ml-2 rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">{{ $share->toText() }}</span>
                </p>
                <p class="mt-1 text-xs text-ink/70">{{ $share->condition }}</p>
            </div>
            <div class="mt-2 text-xs text-ink/50 md:mt-0 md:text-right">
                @if ($share->source)
                    {{ $share->source->title }} ({{ $share->source->reference }})
                @endif
                <p class="text-[10px] text-ink/30">DATA DEMO</p>
            </div>
        </article>
    @empty
        <div class="rounded-xl bg-paper p-8 text-center text-sm text-ink/60">
            Belum ada bagian yang dipetakan (empty state). Silakan cek dokumen <code>docs/database-design.md</code>.
        </div>
    @endforelse
</section>
@endsection
