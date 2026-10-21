<div x-data @keydown.escape.window="$wire.closeDetail()">
    <section>
        <h1 class="text-2xl font-bold md:text-3xl">Ahli Waris</h1>
        <p class="mt-2 text-sm text-ink/70">
            Golongan ahli waris menurut ilmu faraid: dzawil furudh, ashabah, dan dzawil arham.
            Klik kartu atau tombol &laquo;Lihat Detail&raquo; untuk definisi, syarat, kondisi
            mahrum/hijab, serta dalil Al-Qur'an (Arab, Latin, Terjemahan Kemenag) dan pasal KHI.
        </p>
    </section>

    @foreach ($groups as $category => $group)
        <section class="mt-8">
            <h2 class="text-lg font-semibold text-primary">
                {{ $categories[$category] ?? $category }} <span class="text-sm text-ink/50">({{ $group->count() }})</span>
            </h2>

            <div class="mt-3 grid gap-3 md:grid-cols-2">
                @forelse ($group as $heir)
                    <button
                        type="button"
                        wire:click="openDetail({{ $heir->id }})"
                        class="group flex w-full cursor-pointer items-start justify-between gap-3 rounded-xl bg-paper p-4 text-left shadow-sm ring-1 ring-black/5 transition hover:ring-2 hover:ring-accent hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        aria-haspopup="dialog"
                    >
                        <span class="min-w-0 flex-1">
                            <span class="flex items-baseline gap-2">
                                <span class="font-semibold text-primary">{{ $heir->name_id }}</span>
                                @if ($heir->name_ar)
                                    <span class="text-xs italic text-ink/50">{{ $heir->name_ar }}</span>
                                @endif
                                <span class="rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">
                                    {{ $heir->gender === 'm' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </span>
                            <span class="mt-2 block line-clamp-2 text-sm text-ink/80">{{ $heir->detail }}</span>
                            @if ($heir->max_share_numerator !== null)
                                <span class="mt-2 block text-xs text-accent">
                                    Bagian maksimum: {{ $heir->max_share_numerator }}/{{ $heir->max_share_denominator }}
                                </span>
                            @endif
                        </span>
                        <span class="mt-1 shrink-0 self-start rounded-lg border border-accent px-2 py-1 text-xs font-medium text-accent transition group-hover:bg-accent group-hover:text-primary">
                            Lihat Detail <span aria-hidden="true">&rarr;</span>
                        </span>
                    </button>
                @empty
                    <p class="text-sm text-ink/70">Belum ada data.</p>
                @endforelse
            </div>
        </section>
    @endforeach

    @if ($this->selectedHeir !== null && $this->selectedProfile !== null)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="heir-detail-title"
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
                        <p id="heir-detail-title" class="text-base font-bold text-primary md:text-lg">
                            Detail Ahli Waris — {{ $this->selectedHeir->name_id }}
                        </p>
                        <p class="mt-1 text-xs text-ink/60">
                            {{ $this->selectedHeir->name_ar }}
                            {{ $this->selectedHeir->gender === 'm' ? '· Laki-laki' : '· Perempuan' }}
                        </p>
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
                    {{-- (a) Definisi & Pengertian --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Definisi &amp; Pengertian</h2>
                        <p class="mt-2 rounded-xl bg-surface p-4 text-sm text-ink/80">{{ $this->selectedProfile['definisi'] }}</p>
                        <p class="mt-1 text-[10px] text-ink/40">Kategori: {{ $categories[$this->selectedHeir->category] ?? $this->selectedHeir->category }}</p>
                    </section>

                    {{-- (b) Syarat mendapatkan warisan --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Syarat Mendapatkan Warisan</h2>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-ink/80">
                            @foreach ($this->selectedProfile['syarat'] as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                        <h3 class="mt-3 text-sm font-semibold">Syarat umum semua ahli waris (KHI Pasal 171 &amp; 173)</h3>
                        <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                            <li>Memiliki hubungan darah atau perkawinan dengan pewaris.</li>
                            <li>Beragama Islam sama pewaris.</li>
                            <li>Tidak terhalang berdasarkan putusan (KHI Pasal 173: pembunuh, penganiaya berat, fitnah pidana).</li>
                        </ul>
                    </section>

                    {{-- (c) Kondisi mahrum/hijab --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Kondisi Mahrum / Hijab (Penghalang)</h2>
                        <h3 class="mt-2 text-sm font-semibold">Yang menghalangi atau menurunkan bagian ahli waris ini</h3>
                        <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                            @foreach ($this->selectedProfile['dihalangi'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <h3 class="mt-3 text-sm font-semibold">Yang dihalangi / dipengaruhi oleh ahli waris ini</h3>
                        <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                            @foreach ($this->selectedProfile['menghalangi'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>

                    {{-- (d) Dalil Al-Qur'an & Pasal KHI --}}
                    <section>
                        <h2 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Dalil Al-Qur'an &amp; KHI</h2>

                        @if (! empty($this->selectedProfile['verse']))
                            <p class="mt-2 inline-flex rounded bg-accent-soft px-2 py-0.5 text-xs font-semibold text-primary">
                                {{ $this->selectedProfile['verse']['label'] }}
                            </p>
                            <p dir="rtl" lang="ar" class="mt-3 rounded-xl bg-surface p-4 text-right font-serif leading-loose text-xl text-primary md:text-2xl">
                                {{ $this->selectedProfile['verse']['arab'] }}
                            </p>
                            <p class="mt-3 rounded-xl bg-surface p-4 text-sm italic text-ink/80">
                                {{ $this->selectedProfile['verse']['latin'] }}
                                <span class="mt-2 block text-[10px] not-italic text-ink/40">
                                    Nota transliterasi Kemenag: tanda (a)/(un)/(in) adalah harakat akhir bacaan.
                                </span>
                            </p>
                            <div class="mt-3 rounded-xl bg-surface p-4">
                                <p class="text-sm text-ink/80">{{ $this->selectedProfile['verse']['terjemah'] }}</p>
                                <p class="mt-2 text-xs text-ink/50">{{ $this->selectedProfile['verse']['footnote'] }}</p>
                                <p class="mt-2 text-[10px] text-ink/40">
                                    Teks Arab, Latin &amp; Terjemahan: Quran Kemenag — LPMQ Kementerian Agama RI
                                    (Al-Qur'an dan Tafsirnya, edisi yang disempurnakan).
                                </p>
                            </div>
                        @else
                            <p class="mt-2 rounded-xl border border-dashed border-black/10 bg-surface p-4 text-xs text-ink/70">
                                Aplikasi tidak menampilkan ayat khusus untuk ahli waris ini; KHI juga tidak mengatur
                                pasal bagian khususnya. Lihat catatan di bawah.
                            </p>
                        @endif

                        @if (! empty($this->selectedProfile['pasals']))
                            <h3 class="mt-4 text-sm font-semibold">Rujukan KHI (Inpres No. 1 Tahun 1991)</h3>
                            <ul class="mt-2 space-y-2">
                                @foreach ($this->selectedProfile['pasals'] as $pasal)
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

                        @if (! empty($this->selectedProfile['catatan_dalil']))
                            <p class="mt-2 rounded-xl border border-dashed border-black/10 bg-surface p-4 text-xs text-ink/70">
                                {{ $this->selectedProfile['catatan_dalil'] }}
                            </p>
                        @endif
                    </section>
                </div>

                {{-- Footer disclaimer --}}
                <div class="rounded-b-2xl border-t border-black/5 bg-paper p-4 text-center md:p-5">
                    <p class="text-xs text-ink/60">
                        Aplikasi edukasi &amp; simulasi (DEMO) — bukan pengganti ahli faraid maupun nasihat hukum.
                        Kondisi keluarga nyata kerap memiliki pengecualian; minta perhitungan disahkan
                        ahli faraid/ahli waris pada Pengadilan Agama.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
