<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Riwayat Perhitungan Perdata</h1>
    </section>

    @if ($items->isEmpty())
        <div class="u-smart-card p-8 text-center">
            <p class="text-ink/60">Belum ada riwayat perhitungan perdata.</p>
            <a href="{{ route('civil.family') }}" class="mt-3 inline-block text-sm text-[#123D32] underline">Isi formulir data keluarga</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="u-smart-card p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-semibold text-ink">{{ $item->reference_code }}</p>
                        <span class="text-xs text-ink/50">{{ $item->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-ink/60">Status: {{ $item->status }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
