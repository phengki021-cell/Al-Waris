<div x-data @keydown.escape.window="$wire.closeDetail()">
    @include('partials.back-button')
    <section class="mt-3">
        <p class="u-kicker text-gold-deep">Dzawil Furudh</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Bagian-Bagian Warisan</h1>
        <p class="mt-2 text-sm text-ink/70">
            Bagian pasti (dzawil furudh) dari QS. An-Nisaa &amp; KHI. Klik kartu atau tombol
            &laquo;Lihat Detail Dalil&raquo; untuk membuka dalil lengkap: teks Arab, Latin, terjemahan
            Kemenag RI, rujukan KHI, syarat &amp; ketentuan, serta contoh hitungan.
        </p>
    </section>

    {{-- ===== DUA SISTEM HUKUM: Faraid vs Perdata — pembeda yang jelas ===== --}}
    <section class="mt-6 grid gap-3 sm:grid-cols-2" aria-label="Dua sistem hukum waris">
        <div class="relative overflow-hidden rounded-3xl p-5 text-ivory shadow-smooth" style="background:linear-gradient(135deg, #123D32 0%, #147F70 100%)">
            <div class="pointer-events-none absolute inset-0 opacity-[0.08]" aria-hidden="true" style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22><g fill=%22none%22 stroke=%22%23D8B66A%22><rect x=%2212%22 y=%2212%22 width=%2216%22 height=%2216%22/></g></svg>'); background-size:40px 40px"></div>
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.25em] text-gold">Sistem 1 &bull; aktif di halaman ini</p>
            <h2 class="mt-1 text-lg font-bold">Hukum Islam (Faraid)</h2>
            <p class="mt-1.5 text-xs leading-relaxed text-ivory/80">Bagian pasti dari Al-Qur'an &amp; KHI — suami 1/2, istri 1/8, anak perempuan 2/3, dst.</p>
        </div>
        <a href="{{ route('calculate') }}" class="group relative overflow-hidden rounded-3xl bg-paper p-5 shadow-smooth ring-1 ring-black/5 transition hover:ring-gold/40">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.25em] text-charcoal/50">Sistem 2 &bull; mode kalkulator terpisah</p>
            <h2 class="mt-1 text-lg font-bold text-deep">Hukum Perdata Umum (KUHPerdata)</h2>
            <p class="mt-1.5 text-xs leading-relaxed text-charcoal/60">Golongan I: pasangan &amp; anak bagian setara (Pasal 852) + penggantian cucu per cabang (Pasal 853).</p>
            <span class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-[#123D32]">
                Buka tab perdata di kalkulator
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>
    </section>

    {{-- ===== Kartu per ahli waris — satu kartu = satu ahli waris ===== --}}
    <section class="mt-8" aria-label="Daftar bagian per ahli waris">
        @php
            // Satu kartu per ahli waris; kartu berisi semua kondisi bagian orang itu.
            $perHeir = $shares->groupBy(fn ($s) => $s->heir->id)->sortBy(fn ($g) => $g->first()->heir->name_id);
        @endphp
        @forelse ($perHeir as $rules)
            @php $heir = $rules->first()->heir; @endphp
            <div data-reveal class="mb-4 overflow-hidden rounded-3xl bg-paper shadow-smooth ring-1 ring-black/5 transition hover:ring-gold/40">
                {{-- Kepala kartu: gradient hijau + nama + pecahan --}}
                <div class="flex items-center justify-between gap-3 px-5 py-4 text-ivory md:px-6" style="background:linear-gradient(120deg, #123D32 0%, #147F70 100%)">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="truncate text-base font-bold md:text-lg">{{ $heir->name_id }}</h2>
                            <span class="rounded-full bg-white/12 px-2 py-0.5 text-[0.62rem] font-semibold ring-1 ring-white/25">{{ $heir->gender === 'm' ? 'Laki-laki' : 'Perempuan' }}</span>
                            @if ($heir->name_ar)
                                <span class="text-xs italic text-gold-soft">{{ $heir->name_ar }}</span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-[0.65rem] uppercase tracking-[0.2em] text-gold/90">Dzawil Furudh — bagian pasti</p>
                    </div>
                    {{-- Chips pecahan yang dimiliki --}}
                    <div class="flex shrink-0 flex-wrap justify-end gap-1.5">
                        @foreach ($rules as $rule)
                            <span class="tnum grid min-w-11 place-items-center rounded-xl bg-white/10 px-2.5 py-1.5 text-sm font-bold text-gold ring-1 ring-gold/40">{{ $rule->toText() }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- Baris kondisi: klik untuk buka dalil --}}
                <div class="p-3 md:p-4">
                    @foreach ($rules as $rule)
                        <button
                            type="button"
                            wire:click="openDetail({{ $rule->id }})"
                            class="group/row mt-1.5 flex w-full items-center justify-between gap-3 rounded-2xl bg-surface p-4 text-left ring-1 ring-black/5 transition first:mt-0 hover:bg-gold-soft/30 hover:ring-gold/40"
                            aria-haspopup="dialog"
                        >
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-deep">{{ $rule->toText() }} — kondisi {{ $loop->iteration }} dari {{ $rules->count() }}</span>
                                <span class="mt-1 block text-xs leading-relaxed text-ink/70">{{ $rule->condition }}</span>
                                @if ($rule->source)
                                    <span class="mt-1 block text-[0.68rem] text-ink/50">{{ $rule->source->title }} ({{ $rule->source->reference }})</span>
                                @endif
                            </span>
                            <span class="inline-flex shrink-0 items-center gap-1 rounded-xl border border-gold/60 bg-gold-soft/60 px-2.5 py-1.5 text-[0.68rem] font-bold text-gold-deep transition group-hover/row:bg-gold group-hover/row:text-white">
                                Detail Dalil <span aria-hidden="true">&rarr;</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-paper p-8 text-center text-sm text-ink/60 shadow-smooth ring-1 ring-black/5">
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
                class="fixed inset-0 h-full w-full cursor-default bg-emerald-950/60 backdrop-blur-sm"
                wire:click="closeDetail()"
                aria-label="Tutup modal"
                tabindex="-1"
            ></button>

            <div class="relative mx-auto my-4 w-[94%] max-w-2xl overflow-hidden rounded-3xl bg-paper shadow-2xl ring-1 ring-emerald-900/10 md:my-10">
                {{-- Header mewah emerald-gold --}}
                <div class="u-modal-head px-5 py-5 md:px-6 md:py-6">
                    <div class="relative z-[1] flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="u-kicker text-gold-soft">Dalil &amp; Rujukan</p>
                            <p id="dalil-title" class="u-display mt-1.5 text-lg text-ivory md:text-xl">
                                Detail Dalil — {{ $this->selectedShare->heir->name_id }}
                                <span class="ml-1 rounded bg-gold/20 px-2 py-0.5 text-sm text-gold-soft ring-1 ring-gold/40">{{ $this->selectedShare->toText() }}</span>
                            </p>
                            <p class="mt-1.5 text-sm text-gold-soft/90">{{ $this->selectedShare->condition }}</p>
                        </div>
                        <button
                            type="button"
                            wire:click="closeDetail()"
                            class="shrink-0 rounded-xl bg-white/10 p-2 text-ivory ring-1 ring-white/25 transition hover:bg-gold/25 hover:text-white hover:ring-gold/60"
                            aria-label="Tutup modal"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="u-modal-headline relative z-[1] mt-4"></div>
                </div>

                <div class="space-y-6 p-4 md:p-5">
                    {{-- (a) Landasan Al-Qur'an --}}
                    @if (! empty($this->selectedDalil['verse']))
                        <section>
                            <h2 class="u-sec-title">Landasan Al-Qur'an</h2>
                            <p class="mt-2 inline-flex rounded bg-accent-soft px-2 py-0.5 text-xs font-semibold text-primary">
                                {{ $this->selectedDalil['verse']['label'] }}
                            </p>
                            @if ($this->selectedDalil['verse_kelompok'])
                                <p class="mt-2 text-xs text-ink/60">{{ $this->selectedDalil['verse_kelompok'] }}</p>
                            @endif
                            <p dir="rtl" lang="ar" class="verse-arab u-verse-box mt-3 p-5 text-right text-emerald-950">
                                {{ $this->selectedDalil['verse']['arab'] }}
                            </p>
                            <p class="mt-3 rounded-xl bg-surface p-4 text-sm italic text-ink/80">
                                {{ $this->selectedDalil['verse']['latin'] }}
                                <span class="mt-2 block text-[10px] not-italic text-ink/70">
                                    Nota transliterasi Kemenag: tanda (a)/(un)/(in) adalah harakat akhir bacaan.
                                </span>
                            </p>
                            <div class="u-verse-terjemah mt-3 rounded-r-xl bg-surface p-4">
                                <p class="text-sm text-ink/80">{{ $this->selectedDalil['verse']['terjemah'] }}</p>
                                <p class="mt-2 text-xs text-ink/50">{{ $this->selectedDalil['verse']['footnote'] }}</p>
                                <p class="mt-2 text-[10px] text-ink/70">
                                    Teks Arab, Latin &amp; Terjemahan: Quran Kemenag — LPMQ Kementerian Agama RI
                                    (Al-Qur'an dan Tafsirnya, edisi yang disempurnakan).
                                </p>
                            </div>
                        </section>
                    @endif

                    {{-- (b) Rujukan KHI --}}
                    <section>
                        <h2 class="u-sec-title">Rujukan KHI — Kompilasi Hukum Islam (Inpres No. 1 Tahun 1991)</h2>
                        <h3 class="sr-only">Pasal KHI</h3>
                        @if (! empty($this->selectedDalil['pasals']))
                            <ul class="mt-2 space-y-2">
                                @foreach ($this->selectedDalil['pasals'] as $pasal)
                                    <li class="u-pasal-card p-4">
                                        <p class="text-sm font-extrabold uppercase tracking-wide text-emerald-900">{{ $pasal['judul'] }}</p>
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
                        <h2 class="u-sec-title">Syarat &amp; Ketentuan (Dzawil Furudh)</h2>
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
                            <h2 class="u-sec-title">Contoh Ilustrasi Hitungan</h2>
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

                    {{-- Rujukan tambahan: hadis shahih, KUHPerdata, yurisprudensi MA & jurnal ilmiah --}}
                    @include('partials.reference-blocks', [
                        'hadits' => $this->selectedDalil['hadits'],
                        'kuhperdata' => $this->selectedDalil['kuhperdata'],
                        'yurisprudensi' => $this->selectedDalil['yurisprudensi'],
                        'jurnal' => $this->selectedDalil['jurnal'],
                    ])
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
