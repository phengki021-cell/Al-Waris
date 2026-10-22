@extends('layouts.app')

@section('title', 'Ahli Waris')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Ahli Waris</h1>
    <p class="mt-2 text-sm text-ink/70">
        Golongan ahli waris menurut ilmu faraid. Kabar baiknya: datanya diambil dari tabel <code>heirs</code>
        (seeder DEMO) dan terus diperkaya di Tahap berikutnya bersama sumber terkait.
    </p>
</section>

@foreach ($heirs as $category => $group)
    <section class="mt-8">
        <h2 class="text-lg font-semibold text-primary">
            {{ $categories[$category] ?? $category }} <span class="text-sm text-ink/50">({{ $group->count() }})</span>
        </h2>

        <div class="mt-3 grid gap-3 md:grid-cols-2">
            @forelse ($group as $heir)
                <article class="rounded-xl bg-paper p-4 shadow-sm ring-1 ring-black/5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold">{{ $heir->name_id }}</h3>
                            @if ($heir->name_ar) <p class="text-xs italic text-ink/50">{{ $heir->name_ar }}</p> @endif
                        </div>
                        <span class="rounded-full bg-accent-soft px-2 py-0.5 text-xs font-medium text-primary">
                            {{ $heir->gender === 'm' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-ink/80">{{ $heir->detail }}</p>

                    @if ($heir->max_share_numerator !== null)
                        <p class="mt-2 text-xs text-accent">
                            Bagian maksimum: {{ $heir->max_share_numerator }}/{{ $heir->max_share_denominator }}
                        </p>
                    @endif

                    @if ($heir->shares->isNotEmpty())
                        <div class="mt-3 space-y-1 border-t pt-2 text-xs">
                            @foreach ($heir->shares as $share)
                                <p>• {{ $share->numerator }}/{{ $share->denominator }} — {{ $share->condition }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if ($heir->source)
                        <p class="mt-3 text-xs text-ink/50">
                            Sumber: {{ $heir->source->title }} — {{ $heir->source->reference }}
                        </p>
                    @endif
                    <p class="mt-1 text-[10px] text-ink/40">DATA DEMO — verifikasi ahli faraid.</p>
                </article>
            @empty
                <p class="text-sm text-ink/70">Belum ada data.</p>
            @endforelse
        </div>
    </section>
@endforeach
@endsection
