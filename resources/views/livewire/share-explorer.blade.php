<div x-data @keydown.escape.window="$wire.closeDetail()">
    <section>
        <h1 class="text-2xl font-bold md:text-3xl">Bagian-Bagian Warisan</h1>
        <p class="mt-2 text-sm text-ink/70">
            Bagian pasti (dzawil furudh) dari QS. An-Nisaa &amp; KHI. Klik kartu atau tombol
            &laquo;Lihat Detail Dalil&raquo; untuk membuka dalil lengkap: teks Arab, Latin, terjemahan
            Kemenag RI, rujukan KHI, syarat &amp; ketentuan, serta contoh hitungan.
        </p>
    </section>

    <section class="mt-6">
        @forelse ($shares as $share)
            <button
                type="button"
                wire:click="openDetail({{ $share->id }})"
                class="group mb-2 flex w-full cursor-pointer items-start justify-between gap-4 overflow-hidden rounded-xl bg-paper p-4 text-left shadow-sm ring-1 ring-black/5 transition hover:ring-2 hover:ring-accent hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                aria-haspopup="dialog"
            >
                <span class="min-w-0 flex-1">
                    <span class="block text-sm font-semibold">
                        {{ $share->heir->name_id }}
                        <span class="ml-2 rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">{{ $share->toText() }}</span>
                    </span>
                    <span class="mt-1 block text-xs text-ink/70">{{ $share->condition }}</span>
                </span>
                <span class="mt-1 flex shrink-0 flex-col items-end gap-1 text-xs text-ink/50 md:text-right">
                    @if ($share->source)
                        <span>{{ $share->source->title }} ({{ $share->source->reference }})</span>
                    @endif
                    <span class="inline-flex items-center gap-1 rounded border border-accent px-2 py-1 font-medium text-accent transition group-hover:bg-accent group-hover:text-primary">
                        Lihat Detail Dalil <span aria-hidden="true">&rarr;</span>
                    </span>
                    <span class="text-[10px] text-ink/30">DATA DEMO</span>
                </span>
            </button>
        @empty
            <div class="rounded-xl bg-paper p-8 text-center text-sm text-ink/60">
                Belum ada bagian yang dipetakan (empty state). Silakan cek dokumen <code>docs/database-design.md</code>.
            </div>
        @endforelse
    </section>

    @if ($this->selectedShare !== null && $this->selectedDalil !== null)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="dalil-title"
        >
            <button
                type="button"
                class="fixed inset-0 h-full w-full cursor-default bg-black/50"
                wire:click="closeDetail()"
                aria-label="Tutup modal"
                tabindex="-1"
            ></button>

            <div class="relative mx-auto my-4 w-[94%] max-w-2xl rounded-2xl bg-paper shadow-xl ring-1 ring-black/10 md:my-10">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-3 rounded-t-2xl border-b border-black/5 bg-paper p-4 md:p-5">
                    <div class="min-w-0">
                        <p id="dalil-title" class="text-base font-bold text-primary md:text-lg">
                            Detail Dalil — {{ $this->selectedShare->heir->name_id }}
                            <span class="ml-1 rounded bg-accent-soft px-2 py-0.5 text-sm text-primary">{{ $this->selectedShare->toText() }}</span>
                        </p>
                        <p class="mt-1 text-xs text-ink/60">{{ $this->selectedShare->condition }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeDetail()"
                        class="shrink-0 rounded-lg bg-surface p-2 text-ink/70 transition hover:bg-accent-soft hover:text-primary"
                        aria-label="Tutup modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-6 p-4 md:p-5">
                    {{-- (a) Landasan Al-Qur'an --}}
                    @if (! empty($this->selectedDalil['verse']))
                        <section>
                            <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Landasan Al-Qur'an</h2>
                            <p class="mt-2 inline-flex rounded bg-accent-soft px-2 py-0.5 text-xs font-semibold text-primary">
                                {{ $this->selectedDalil['verse']['label'] }}
                            </p>
                            @if ($this->selectedDalil['verse_kelompok'])
                                <p class="mt-2 text-xs text-ink/60">{{ $this->selectedDalil['verse_kelompok'] }}</p>
                            @endif
                            <p dir="rtl" lang="ar" class="mt-3 rounded-xl bg-surface p-4 text-right font-serif leading-loose text-xl text-primary md:text-2xl">
                                {{ $this->selectedDalil['verse']['arab'] }}
                            </p>
                            <p class="mt-3 rounded-xl bg-surface p-4 text-sm italic text-ink/80">
                                {{ $this->selectedDalil['verse']['latin'] }}
                                <span class="mt-2 block text-[10px] not-italic text-ink/40">
                                    Nota transliterasi Kemenag: tanda (a)/(un)/(in) adalah harakat akhir bacaan.
                                </span>
                            </p>
                            <div class="mt-3 rounded-xl bg-surface p-4">
                                <p class="text-sm text-ink/80">{{ $this->selectedDalil['verse']['terjemah'] }}</p>
                                <p class="mt-2 text-xs text-ink/50">{{ $this->selectedDalil['verse']['footnote'] }}</p>
                                <p class="mt-2 text-[10px] text-ink/40">
                                    Teks Arab, Latin &amp; Terjemahan: Quran Kemenag — LPMQ Kementerian Agama RI
                                    (Al-Qur'an dan Tafsirnya, edisi yang disempurnakan).
                                </p>
                            </div>
                        </section>
                    @endif

                    {{-- (b) Rujukan KHI --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Rujukan KHI — Kompilasi Hukum Islam (Inpres No. 1 Tahun 1991)</h2>
                        <h3 class="sr-only">Pasal KHI</h3>
                        @if (! empty($this->selectedDalil['pasals']))
                            <ul class="mt-2 space-y-2">
                                @foreach ($this->selectedDalil['pasals'] as $pasal)
                                    <li class="rounded-xl bg-surface p-4">
                                        <p class="text-sm font-semibold text-primary">{{ $pasal['judul'] }}</p>
                                        <p class="mt-1 text-sm text-ink/80">{{ $pasal['teks'] }}</p>
                                        @if (! empty($pasal['catatan']))
                                            <p class="mt-2 text-xs italic text-ink/60">{{ $pasal['catatan'] }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if (! empty($this->selectedDalil['khi_catatan']))
                            <p class="mt-2 rounded-xl border border-dashed border-black/10 bg-surface p-4 text-xs text-ink/70">
                                {{ $this->selectedDalil['khi_catatan'] }}
                            </p>
                        @endif
                    </section>

                    {{-- (c) Syarat & Ketentuan --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Syarat &amp; Ketentuan (Dzawil Furudh)</h2>
                        <h3 class="mt-2 text-sm font-semibold">Syarat munculnya bagian ini</h3>
                        <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                            @foreach ($this->selectedDalil['syarat'] as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                        <h3 class="mt-3 text-sm font-semibold">Syarat umum ahli waris (berlaku untuk semua bagian)</h3>
                        <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                            @foreach ($this->selectedDalil['syarat_umum'] as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                    </section>

                    {{-- (d) Contoh ilustrasi hitungan --}}
                    @if (! empty($this->selectedDalil['contoh']))
                        <section>
                            <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Contoh Ilustrasi Hitungan</h2>
                            <div class="mt-2 rounded-xl bg-surface p-4">
                                <p class="text-sm font-semibold text-primary">{{ $this->selectedDalil['contoh']['judul'] }}</p>
                                <ol class="mt-2 list-inside list-decimal space-y-1 text-sm text-ink/80">
                                    @foreach ($this->selectedDalil['contoh']['langkah'] as $langkah)
                                        <li>{{ $langkah }}</li>
                                    @endforeach
                                </ol>
                                <p class="mt-3 rounded bg-accent-soft px-2 py-1 text-xs text-primary">{{ $this->selectedDalil['contoh']['catatan'] }}</p>
                            </div>
                        </section>
                    @endif
                </div>

                {{-- Footer disclaimer --}}
                <div class="rounded-b-2xl border-t border-black/5 bg-paper p-4 text-center md:p-5">
                    <p class="text-xs text-ink/60">
                        Aplikasi edukasi &amp; simulasi (DEMO) — bukan pengganti ahli faraid maupun nasihat hukum.
                        Rujukan berpasal: sesuai database <code>sources</code>; untuk kasus nyata minta perhitungan
                        disahkan oleh ahli faraid/ahli waris di Pengadilan Agama.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
