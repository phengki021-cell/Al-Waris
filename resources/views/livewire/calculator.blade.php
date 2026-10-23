<div x-data class="mx-auto max-w-4xl">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="u-kicker text-gold-deep">Kalkulator</p>
            <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Hitung Warisan Otomatis</h1>
        </div>
        <span class="u-chip rounded-full">DEMO — verifikasi ahli faraid</span>
    </div>
    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/70">
        Alat <strong class="text-ink">edukasi &amp; simulasi</strong> faraid — bukan pengganti ahli faraid atau
        nasihat hukum. Pilih mode perhitungan pada <strong class="text-ink">glass bar</strong> di bawah: tab Faraidh
        utama bekerja persis seperti sebelumnya.
    </p>

    {{-- ===== Tab switcher — glassmorphism card ikon pilihan mode ===== --}}
    <div class="u-glassbar mt-6 grid gap-1.5 p-1.5 sm:grid-cols-3" role="tablist" aria-label="Mode kalkulator">
        <button
            type="button"
            role="tab"
            wire:click="setTab('faraid')"
            aria-selected="{{ $tab === 'faraid' ? 'true' : 'false' }}"
            class="u-tab {{ $tab === 'faraid' ? 'u-tab-active text-white' : 'text-ink/65' }} px-4 py-3">
            <span class="flex items-center gap-2.5 text-left">
                <span class="u-icon-box u-tab-icon h-9 w-9 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                        <path d="M21 13A9 9 0 1 1 11 3a7 7 0 0 0 10 10Z"/>
                        <path d="M17 5.4l.8 1.7 1.7.8-1.7.8L17 10.4l-.8-1.7-1.7-.8 1.7-.8.8-1.7Z" fill="currentColor" stroke="none"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-bold leading-tight">Faraidh — Utama</span>
                    <span class="block text-[10px] leading-snug {{ $tab === 'faraid' ? 'text-gold-soft/90' : 'text-ink/50' }}">Hukum waris Islam · Qur'an, Sunnah &amp; KHI</span>
                </span>
            </span>
        </button>

        <button
            type="button"
            role="tab"
            wire:click="setTab('civil')"
            aria-selected="{{ $tab === 'civil' ? 'true' : 'false' }}"
            class="u-tab {{ $tab === 'civil' ? 'u-tab-active text-white' : 'text-ink/65' }} px-4 py-3">
            <span class="flex items-center gap-2.5 text-left">
                <span class="u-icon-box u-tab-icon h-9 w-9 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                        <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/>
                        <path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/>
                        <path d="M7 21h10"/>
                        <path d="M12 3v18"/>
                        <path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-bold leading-tight">KUHPerdata</span>
                    <span class="block text-[10px] leading-snug {{ $tab === 'civil' ? 'text-gold-soft/90' : 'text-ink/50' }}">Mode opsional Hukum Waris Perdata</span>
                </span>
            </span>
        </button>

        <button
            type="button"
            role="tab"
            wire:click="setTab('kontemporer')"
            aria-selected="{{ $tab === 'kontemporer' ? 'true' : 'false' }}"
            class="u-tab {{ $tab === 'kontemporer' ? 'u-tab-active text-white' : 'text-ink/65' }} px-4 py-3">
            <span class="flex items-center gap-2.5 text-left">
                <span class="u-icon-box u-tab-icon h-9 w-9 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                        <path d="M12 7v14"/>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-bold leading-tight">Kontemporer</span>
                    <span class="block text-[10px] leading-snug {{ $tab === 'kontemporer' ? 'text-gold-soft/90' : 'text-ink/50' }}">Yurisprudensi MA RI &amp; topik pembanding</span>
                </span>
            </span>
        </button>
    </div>

    @if ($tab === 'faraid')
        {{-- =====================================================
             Panel Faraidh UTAMA — flow asli tidak berubah
             ===================================================== --}}
        <form wire:submit="calculate" class="mt-6 space-y-5">
            {{-- === 1. SMART CARDS: Harta & urutan eksekusi kewajiban === --}}
            <div class="relative">
                <fieldset class="rounded-2xl border border-black/5 bg-surface/60 p-4 md:p-5">
                    <legend class="u-kicker px-1 text-emerald-900/70">1 · Harta &amp; kewajiban</legend>

                    {{-- Strip urutan eksekusi harta (1→4) --}}
                    <ol class="mb-4 flex flex-wrap items-center gap-1.5 text-[0.68rem] font-bold tracking-wide uppercase text-ink/70" aria-label="Urutan eksekusi harta">
                        <li class="rounded-full bg-white px-2.5 py-1 ring-1 ring-emerald-900/10 text-emerald-900">Harta Peninggalan</li>
                        <li aria-hidden="true" class="text-gold-deep">→</li>
                        <li class="rounded-full bg-gold-soft px-2.5 py-1 text-gold-deep ring-1 ring-gold/40">1 · Biaya Jenazah</li>
                        <li aria-hidden="true" class="text-gold-deep">→</li>
                        <li class="rounded-full bg-gold-soft px-2.5 py-1 text-gold-deep ring-1 ring-gold/40">2 · Hutang</li>
                        <li aria-hidden="true" class="text-gold-deep">→</li>
                        <li class="rounded-full bg-gold-soft px-2.5 py-1 text-gold-deep ring-1 ring-gold/40">3 · Wasiat</li>
                        <li aria-hidden="true" class="text-gold-deep">→</li>
                        <li class="rounded-full bg-emerald-soft px-2.5 py-1 text-emerald-900 ring-1 ring-emerald-700/30">4 · Harta Bersih (Gono-Gini)</li>
                    </ol>

                    <div class="grid gap-3 md:grid-cols-2">
                        {{-- Kartu harta dasar --}}
                        <div class="u-smart-card p-4 md:col-span-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"/><path d="M4 8v13h16V8"/><path d="M12 12v5"/><path d="M9 17h6"/></svg>
                                    </span>
                                    <label for="estate" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Total harta peninggalan</label>
                                </div>
                                <span class="u-chip">harta</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="estate" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('estate')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('estate'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('estate')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- 4 · Harta bersih govo-goni indikator: pemisahan pihak --}}
                        <div class="rounded-2xl border border-dashed border-gold/45 bg-gold-soft/50 p-4">
                            <div class="flex items-center gap-2.5">
                                <span class="u-step-badge">4</span>
                                <p class="text-[0.78rem] font-bold leading-snug text-emerald-900 md:text-sm">Harta bersih dibagi (Gono-Gini)</p>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-ink/70">
                                Pastikan <strong class="text-ink">hak pasangan atas harta bersama</strong> (Gono-Gini)
                                dipisahkan lebih dulu — isikan hanya bagian pewaris.
                                Urutan kewajiban: <strong class="text-ink">jenazah → hutang → wasiat (≤ 1/3)</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 grid gap-3 md:grid-cols-3">
                        {{-- 1 · Biaya jenazah --}}
                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 8v5m0 3h.01"/><circle cx="12" cy="12" r="9"/></svg>
                                    </span>
                                    <label for="funeral" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Biaya pengurusan jenazah</label>
                                </div>
                                <span class="u-step-badge">1</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="funeral" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('funeral')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('funeral'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('funeral')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- 2 · Hutang --}}
                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M7 4h10l4 4h-4"></path><path d="M17 20h-6"/><path d="M7 4v16h10V8"/><path d="M10 12h4M10 16h4"/></svg>
                                    </span>
                                    <label for="debts" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Hutang almarhum</label>
                                </div>
                                <span class="u-step-badge">2</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="debts" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('debts')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('debts'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('debts')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- 3 · Wasiat --}}
                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                    </span>
                                    <label for="bequest" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Wasiat (maks 1/3 — divalidasi)</label>
                                </div>
                                <span class="u-step-badge">3</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="bequest" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('bequest')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('bequest'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('bequest')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </fieldset>
            </div>

            {{-- === 2. APLIKASI AHLI WARIS INTERAKTIF (visual choice cards) === --}}
            <fieldset class="rounded-2xl border border-black/5 bg-surface/60 p-4 md:p-5">
                <legend class="u-kicker px-1 text-emerald-900/70">2 · Ahli waris yang masih hidup</legend>

                {{-- Pasangan: kartu pilihan visual --}}
                <div class="mt-3 grid gap-2 sm:grid-cols-3">
                    <button type="button"
                        x-on:click="$wire.set('spouse', '')"
                        :class="$wire.get('spouse') === '' ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('spouse') === '' ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="10" cy="8" r="3.5"/><path d="M3.5 21a6.5 6.5 0 0 1 13 0"/><path d="M19 8.5 21 10m0 0-2 1.5M21 10h-3.5"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-bold">Tanpa pasangan</span>
                            <span class="block text-[10px] text-ink/50">kosongkan pilihan</span>
                        </span>
                    </button>

                    <button type="button"
                        x-on:click="$wire.set('spouse', 'husband')"
                        :class="$wire.get('spouse') === 'husband' ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('spouse') === 'husband' ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="7.5" r="3.5"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M12 11.5v2l-1.2 3 1.2 3 1.2-3-1.2-3v-2" fill="currentColor" stroke="none"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-bold">Suami</span>
                            <span class="block text-[10px] text-ink/50">dzawil furudh 1/2 — ashabah sisa</span>
                        </span>
                    </button>

                    <button type="button"
                        x-on:click="$wire.set('spouse', 'wife')"
                        :class="$wire.get('spouse') === 'wife' ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('spouse') === 'wife' ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="7.5" r="3.5"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M12 11.5l-1.6 2 1.6 1.6 1.6-1.6-1.6-2Z" fill="currentColor" stroke="none"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-bold">Istri</span>
                            <span class="block text-[10px] text-ink/50">1/4 atau 1/8 bila ada anak</span>
                        </span>
                    </button>
                </div>

                {{-- Jumlah istri — counter badge bila Istri dipilih --}}
                <div x-show="$wire.get('spouse') === 'wife'" x-cloak
                    class="u-smart-card mt-2 flex flex-wrap items-center justify-between gap-3 p-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="7" cy="8" r="3"/><path d="M2 20a5 5 0 0 1 10 0"/><circle cx="17" cy="8" r="3"/><path d="M12 20a5 5 0 0 1 10 0"/></svg>
                        </span>
                        <p class="text-xs font-bold text-ink">Jumlah istri <span class="font-medium text-ink/50">(1–4 sesuai syariat)</span></p>
                        @error('wifeCount')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" class="u-step-btn" aria-label="Kurangi jumlah istri"
                            x-on:click="$wire.set('wifeCount', Math.max(1, $wire.get('wifeCount') - 1))">−</button>
                        <span class="tnum min-w-9 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('wifeCount')"></span>
                        <button type="button" class="u-step-btn" aria-label="Tambah jumlah istri"
                            x-on:click="$wire.set('wifeCount', Math.min(4, $wire.get('wifeCount') + 1))">+</button>
                    </div>
                </div>

                {{-- Anak & saudara — counter badge cards --}}
                <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="6.5" r="2.8"/><path d="M6.5 20a5.5 5.5 0 0 1 11 0"/><path d="M12 12v3m0 0-1.8-1.8M12 15l1.8-1.8"/></svg>
                            </span>
                            <label for="sons" class="text-xs font-bold leading-tight text-ink">Anak<br class="hidden sm:block"> laki-laki</label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi anak laki-laki" x-on:click="$wire.set('sons', Math.max(0, $wire.get('sons') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('sons')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah anak laki-laki" x-on:click="$wire.set('sons', Math.min(30, $wire.get('sons') + 1))">+</button>
                        </div>
                        @error('sons')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="6.5" r="2.8"/><path d="M6.5 20a5.5 5.5 0 0 1 11 0"/><path d="M12 12v3m0 0-1.8-1.8M12 15l1.8-1.8"/><path d="M12 20.5h.01" fill="currentColor"/></svg>
                            </span>
                            <label for="daughters" class="text-xs font-bold leading-tight text-ink">Anak<br class="hidden sm:block"> perempuan</label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi anak perempuan" x-on:click="$wire.set('daughters', Math.max(0, $wire.get('daughters') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('daughters')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah anak perempuan" x-on:click="$wire.set('daughters', Math.min(30, $wire.get('daughters') + 1))">+</button>
                        </div>
                        @error('daughters')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="8" cy="7" r="2.8"/><circle cx="16" cy="9" r="2.8"/><path d="M3 20a5 5 0 0 1 7.5-4.3M10 20a5.4 5.4 0 0 1 10.8 0"/></svg>
                            </span>
                            <label for="brothers" class="text-xs font-bold leading-tight text-ink">Saudara<br class="hidden sm:block"> laki-laki</label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi saudara laki-laki" x-on:click="$wire.set('brothers', Math.max(0, $wire.get('brothers') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('brothers')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah saudara laki-laki" x-on:click="$wire.set('brothers', Math.min(30, $wire.get('brothers') + 1))">+</button>
                        </div>
                        @error('brothers')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="8" cy="7" r="2.8"/><circle cx="16" cy="9" r="2.8"/><path d="M3 20a5 5 0 0 1 7.5-4.3M10 20a5.4 5.4 0 0 1 10.8 0"/><path d="M15.5 12.5h.01M18 11h.01" fill="currentColor"/></svg>
                            </span>
                            <label for="sisters" class="text-xs font-bold leading-tight text-ink">Saudara<br class="hidden sm:block"> perempuan</label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi saudara perempuan" x-on:click="$wire.set('sisters', Math.max(0, $wire.get('sisters') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('sisters')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah saudara perempuan" x-on:click="$wire.set('sisters', Math.min(30, $wire.get('sisters') + 1))">+</button>
                        </div>
                        @error('sisters')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Orang tua, nenek & kakek (pengganti ayah) — visual toggle cards --}}
                <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                    <button type="button"
                        x-on:click="$wire.toggle('father')"
                        :class="$wire.get('father') ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('father') ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="7" r="3.4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M17 3.5h4M19 1.5v4"/></svg>
                        </span>
                        <span>AYAH · 1/6 <span class="block text-[10px] font-medium text-ink/45">klik untuk masukkan</span></span>
                    </button>

                    <button type="button"
                        x-on:click="$wire.toggle('mother')"
                        :class="$wire.get('mother') ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('mother') ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="7" r="3.4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M12 1.2l.8 1.7 1.7.8-1.7.8-.8 1.7-.8-1.7-1.7-.8 1.7-.8.8-1.7Z" fill="currentColor" stroke="none"/></svg>
                        </span>
                        <span>IBU · 1/6 <span class="block text-[10px] font-medium text-ink/45">klik untuk masukkan</span></span>
                    </button>

                    <button type="button"
                        x-on:click="$wire.toggle('maternalGrandmother')"
                        :class="$wire.get('maternalGrandmother') ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('maternalGrandmother') ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="7" r="3.4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M19.5 1 17.5 5h4L19.5 9"/></svg>
                        </span>
                        <span>NENEK DARI IBU <span class="block text-[10px] font-medium text-ink/45">klik untuk masukkan</span></span>
                    </button>

                    <button type="button"
                        x-on:click="$wire.toggle('grandfather')"
                        :class="$wire.get('grandfather') ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                        :aria-pressed="$wire.get('grandfather') ? 'true' : 'false'">
                        <span class="u-icon-box h-9 w-9 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="8" cy="7" r="3.4"/><path d="M1.5 21a6.5 6.5 0 0 1 13 0"/><path d="M17 1.5h4l-2 3.5"/><path d="M19 1.5v3"/></svg>
                        </span>
                        <span>KAKEK DARI AYAH <span class="block text-[10px] font-medium text-ink/45">pengganti ayah · klik untuk masukkan</span></span>
                    </button>
                </div>

                {{-- Ahli waris pengganti: cucu menggantikan ayahnya yang telah wafat --}}
                <p class="mt-3 text-[0.68rem] font-extrabold uppercase tracking-widest text-ink/45">Ahli waris pengganti — perwakilan anak yang telah wafat</p>
                <div class="mt-2 grid gap-2 sm:grid-cols-2">
                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="9" cy="6.5" r="2.8"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><circle cx="16.5" cy="8" r="2.4"/><path d="M12.5 20a4.4 4.4 0 0 1 8.5-1.6"/></svg>
                            </span>
                            <label for="grandsons" class="text-xs font-bold leading-tight text-ink">Cucu laki-laki<br class="hidden sm:block"> <span class="font-medium text-ink/50">(dari anak laki yang wafat)</span></label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi cucu laki-laki" x-on:click="$wire.set('grandsons', Math.max(0, $wire.get('grandsons') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('grandsons')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah cucu laki-laki" x-on:click="$wire.set('grandsons', Math.min(30, $wire.get('grandsons') + 1))">+</button>
                        </div>
                        @error('grandsons')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="9" cy="6.5" r="2.8"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><circle cx="16.5" cy="8" r="2.4"/><path d="M12.5 20a4.4 4.4 0 0 1 8.5-1.6"/><path d="M16.5 12h.01M18.8 11h.01" fill="currentColor"/></svg>
                            </span>
                            <label for="granddaughters" class="text-xs font-bold leading-tight text-ink">Cucu perempuan<br class="hidden sm:block"> <span class="font-medium text-ink/50">(dari anak laki yang wafat)</span></label>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5">
                            <button type="button" class="u-step-btn" aria-label="Kurangi cucu perempuan" x-on:click="$wire.set('granddaughters', Math.max(0, $wire.get('granddaughters') - 1))">−</button>
                            <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('granddaughters')"></span>
                            <button type="button" class="u-step-btn" aria-label="Tambah cucu perempuan" x-on:click="$wire.set('granddaughters', Math.min(30, $wire.get('granddaughters') + 1))">+</button>
                        </div>
                        @error('granddaughters')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <p class="mt-2 text-xs leading-relaxed text-ink/60">
                    Perwakilan (siwah): cucu menggantikan <strong class="text-ink">ayahnya</strong> — aktif bila tidak ada
                    anak laki-laki langsung; kakek bersama cucu/saudara sengaja ditolak mesin agar tidak salah hitung (umariyyatân).
                </p>

                {{-- Ringkasan input (mengikuti jejak asli) --}}
                <div class="u-smart-card mt-4 overflow-hidden">
                    <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-900 to-emerald-700 px-4 py-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-4 w-4 text-gold-soft" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path stroke-linecap="round" d="M9 12h6M9 16h6M9 8h2"/></svg>
                        <p class="text-xs font-extrabold tracking-widest text-gold-soft uppercase">Ringkasan input</p>
                    </div>
                    <div class="space-y-1.5 px-4 py-3.5 text-xs text-ink/80">
                        <p>
                            Harta awal: <strong class="tnum text-ink">Rp {{ number_format((float) ($this->estate ?: 0), 2, ',', '.') }}</strong>
                            · Kewajiban (jenazah + hutang + wasiat):
                            <strong class="tnum text-ink">Rp {{ number_format((float) ($this->funeral ?: 0) + (float) ($this->debts ?: 0) + (float) ($this->bequest ?: 0), 2, ',', '.') }}</strong>
                        </p>
                        <p>
                            Ahli waris:
                            {{ trim(($this->spouse === 'husband' ? 'Suami, ' : ($this->spouse === 'wife' ? 'Istri' . ($this->wifeCount > 1 ? ' (×' . $this->wifeCount . ')' : '') . ', ' : '')) . collect([
                                $this->sons > 0 ? 'anak laki-laki ' . $this->sons : '',
                                $this->daughters > 0 ? 'anak perempuan ' . $this->daughters : '',
                                $this->father ? 'ayah' : '',
                                $this->mother ? 'ibu' : '',
                                $this->maternalGrandmother ? 'nenek dari ibu' : '',
                                $this->grandfather ? 'kakek dari ayah' : '',
                                $this->brothers > 0 ? 'saudara laki-laki ' . $this->brothers : '',
                                $this->sisters > 0 ? 'saudara perempuan ' . $this->sisters : '',
                                $this->grandsons > 0 ? 'cucu laki-laki (dari anak laki) ' . $this->grandsons : '',
                                $this->granddaughters > 0 ? 'cucu perempuan (dari anak laki) ' . $this->granddaughters : '',
                            ])->filter()->implode(', ')) ?: '— belum ada yang diisi —' }}
                        </p>
                        <p class="mt-2 flex flex-wrap gap-x-3 font-semibold text-emerald-900 underline-offset-2">
                            <a class="text-gold-deep underline transition hover:text-emerald-900" href="{{ route('how-to') }}">Pelajari cara menghitungnya</a>
                            <a class="text-gold-deep underline transition hover:text-emerald-900" href="{{ route('shares') }}">Lihat bagian ahli waris</a>
                        </p>
                    </div>
                </div>

                {{-- Papan keterangan dinamis: berubah langsung mengikuti pilihan --}}
                <div class="u-smart-card mt-4 overflow-hidden" aria-live="polite">
                    <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-700 px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-4 w-4 text-gold-soft" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9-9 9-9-9 9-9 9 9"/></svg>
                            <p class="text-xs font-extrabold uppercase tracking-widest text-gold-soft">Keterangan setiap ahli waris</p>
                        </div>
                        <span class="text-[10px] font-medium text-emerald-100/80">berubah otomatis mengikuti pilihan di atas</span>
                    </div>

                    @if ($showResult && $result && ($result['status'] ?? null) === 'valid')
                        <p class="rounded-xl bg-emerald-50 px-4 py-2.5 text-xs font-medium text-emerald-900 ring-1 ring-emerald-200 m-3">
                            Hasil final sudah dihitung — lihat panel hasil di bawah. Papan ini menunjukkan keterangan sementara sesuai pilihan saat ini.
                        </p>
                    @endif

                    @if ($heirEntriesNote)
                        <p class="mx-3 mt-3 rounded-xl bg-gold-soft/60 px-4 py-2.5 text-xs leading-relaxed text-amber-900 ring-1 ring-gold/30">
                            {{ $heirEntriesNote }}
                        </p>
                    @endif

                    <div class="grid gap-2 p-3 sm:grid-cols-2">
                        @foreach ($heirEntries as $entry)
                            <div class="rounded-md p-3 ring-1 @if ($entry['status'] === 'fard') bg-emerald-soft/70 ring-emerald-700/25
                                @elseif($entry['status'] === 'asabah') bg-gold-soft/40 ring-gold/30
                                @elseif($entry['status'] === 'mahjub') bg-red-50 ring-red-200
                                @elseif($entry['status'] === 'pending') bg-red-50/60 ring-red-200
                                @else bg-white ring-black/8 @endif">
                                <div class="flex flex-wrap items-center justify-between gap-1.5">
                                    <p class="text-sm font-bold text-ink">
                                        {{ $entry['name'] }}
                                        @if ($entry['count'] > 1)
                                            <span class="text-xs text-ink/55">(×{{ $entry['count'] }})</span>
                                        @endif
                                    </p>
                                    <span class="rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider ring-1
                                        @if ($entry['status'] === 'fard') bg-emerald-900 text-emerald-50 ring-emerald-900
                                        @elseif($entry['status'] === 'asabah') bg-gold-soft text-gold-deep ring-gold/40
                                        @elseif($entry['status'] === 'mahjub') bg-red-600 text-white ring-red-600
                                        @elseif($entry['status'] === 'pending') bg-white text-red-700 ring-red-300
                                        @else bg-white text-ink/45 ring-black/10 @endif">
                                        {{ $entry['label'] }}
                                    </span>
                                </div>

                                @if (in_array($entry['status'], ['fard', 'asabah'], true))
                                    <p class="tnum mt-1 text-xs font-semibold text-emerald-900/85">
                                        Bagian {{ $entry['share_text'] }}
                                        @if ($entry['per_person_share'])
                                            <span class="text-ink/45">· per orang {{ $entry['per_person_share'] }}</span>
                                        @endif
                                    </p>
                                    @if ($entry['amount_text'])
                                        <p class="tnum mt-0.5 text-sm font-extrabold text-emerald-900">
                                            {{ $entry['amount_text'] }}
                                            @if ($entry['per_person_amount_text'])
                                                <span class="text-[11px] font-bold text-ink/55">· per orang {{ $entry['per_person_amount_text'] }}</span>
                                            @endif
                                        </p>
                                    @endif
                                @endif

                                <p class="mt-1 text-[11px] leading-relaxed text-ink/65">{{ $entry['reason'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if (! $heirEntriesNote && ! collect($heirEntries)->contains(fn ($e) => $e['status'] === 'mahjub' || $e['status'] === 'pending'))
                        <p class="px-4 pb-3 text-[11px] text-ink/50">
                            Belum memilih apa pun? Kartu bagian masih kosong — pilih pasangan, orang tua, atau isi jumlah anak untuk melihat perubahannya.
                        </p>
                    @endif
                </div>

                {{-- ===== Panel 'ASABAH (tiga jenis) — terpisah dari hijab ===== --}}
                <div class="u-smart-card mt-4 overflow-hidden" aria-live="polite">
                    <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-700 px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-4 w-4 text-gold-soft" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16 16 3-8 3 8M2 16l3-8 3 8m7 5H9l3-8 3 8m0-16L12 5l-1 1"/></svg>
                            <p class="text-xs font-extrabold uppercase tracking-widest text-gold-soft">Jenis &lsquo;Ashabah</p>
                        </div>
                        <span class="text-[10px] font-medium text-emerald-100/80">ahli waris yang menerima sisa harta — tiga jenis</span>
                    </div>
                    <p class="px-4 pt-3 text-[11px] leading-relaxed text-ink/60">
                        Jenis: <strong class="text-ink">bin nafsi</strong> (dari dirinya sendiri — laki-laki menurun / ayah &amp; kakek menyerap sisa),
                        <strong class="text-ink">bil ghair</strong> (perempuan bersama laki-laki sederajat — 2 bagian laki-laki : 1 perempuan),
                        <strong class="text-ink">ma&rsquo;al ghair</strong> (saudara perempuan membagi sisa bersama anak/cucu perempuan).
                    </p>

                    @php
                        $asabahPanel = collect($heirEntries)->filter(fn ($e) => $e['status'] === 'asabah');
                    @endphp

                    @if ($heirEntriesNote)
                        <p class="mx-4 mt-3 rounded-xl bg-gold-soft/60 px-4 py-2.5 text-xs leading-relaxed text-amber-900 ring-1 ring-gold/30">
                            Panel &lsquo;ashabah menunggu: {{ $heirEntriesNote }}
                        </p>
                    @elseif ($asabahPanel->isEmpty())
                        <p class="px-4 py-3 text-[11px] text-ink/50">
                            Belum ada &lsquo;ashabah pada pilihan saat ini. Jika sisa harta ada dan ada ahli waris yang menyerapnya, kartu di bawah otomatis terisi.
                        </p>
                    @else
                        <div class="grid gap-2 p-3 sm:grid-cols-2">
                            @foreach ($asabahPanel as $a)
                                <div class="rounded-lg bg-gold-soft/30 p-3 ring-1 ring-gold/30">
                                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                                        <p class="text-sm font-bold text-ink">
                                            {{ $a['name'] }}
                                            @if ($a['count'] > 1)
                                                <span class="text-xs text-ink/55">(×{{ $a['count'] }})</span>
                                            @endif
                                        </p>
                                        <span class="rounded-full bg-gold-soft px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-gold-deep ring-1 ring-gold/40">{{ $a['asabah_type_label'] }}</span>
                                    </div>
                                    <p class="mt-1 text-[11px] leading-relaxed text-ink/70">{{ $a['reason'] }}</p>
                                    <p class="mt-1 text-[11px] leading-relaxed text-ink/60">{{ $a['asabah_way'] }}</p>
                                    <p class="tnum mt-1.5 text-xs font-semibold text-emerald-900/85">
                                        Bagian {{ $a['share_text'] }}
                                        @if ($a['per_person_share'])
                                            <span class="text-ink/45">· per orang {{ $a['per_person_share'] }}</span>
                                        @endif
                                    </p>
                                    @if ($a['amount_text'])
                                        <p class="tnum mt-0.5 text-sm font-extrabold text-emerald-900">
                                            {{ $a['amount_text'] }}
                                            @if ($a['per_person_amount_text'])
                                                <span class="text-[11px] font-bold text-ink/55">· per orang {{ $a['per_person_amount_text'] }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ===== Panel HIJAB (hirman & nuqshan) — terpisah dari 'ashabah ===== --}}
                <div class="u-smart-card mt-4 overflow-hidden" aria-live="polite">
                    <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-red-900 via-red-800 to-red-700 px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-4 w-4 text-red-50" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3a6 6 0 0 0-6 6v3l-2 4h16l-2-4V9a6 6 0 0 0-6-6M10 20a2 2 0 0 0 4 0"/></svg>
                            <p class="text-xs font-extrabold uppercase tracking-widest text-red-50">Hijab (Penghalang Warisan)</p>
                        </div>
                        <span class="text-[10px] font-medium text-red-100/80">hijab hirman &amp; hijab nuqshan</span>
                    </div>
                    <p class="px-4 pt-3 text-[11px] leading-relaxed text-ink/60">
                        <strong class="text-ink">Hirman</strong> = terhalang seluruhnya (tidak memperoleh apa-apa).
                        <strong class="text-ink">Nuqshan</strong> = bagiannya berkurang (masih menerima). Keduanya muncul otomatis mengikuti pilihan.
                    </p>

                    @php
                        $hijabPanel = collect($heirEntries)->filter(fn ($e) => $e['hijab'] !== null);
                    @endphp

                    @if ($heirEntriesNote)
                        <p class="mx-4 mt-3 rounded-xl bg-red-50 px-4 py-2.5 text-xs leading-relaxed text-red-900 ring-1 ring-red-200">
                            Panel hijab menunggu: {{ $heirEntriesNote }}
                        </p>
                    @elseif ($hijabPanel->isEmpty())
                        <p class="px-4 py-3 text-[11px] text-ink/50">
                            Belum ada hijab pada pilihan saat ini — seluruh ahli waris yang dipilih tetap menerima bagiannya.
                        </p>
                    @else
                        <div class="grid gap-2 p-3 sm:grid-cols-2">
                            @foreach ($hijabPanel as $hijabEntry)
                                @php $hij = $hijabEntry['hijab']; @endphp
                                <div class="rounded-lg p-3 ring-1 {{ $hij['type'] === 'hirman' ? 'bg-red-50 ring-red-200' : 'bg-gold-soft/30 ring-gold/30' }}">
                                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                                        <p class="text-sm font-bold text-ink">
                                            {{ $hijabEntry['name'] }}
                                            @if ($hijabEntry['count'] > 1)
                                                <span class="text-xs text-ink/55">(×{{ $hijabEntry['count'] }})</span>
                                            @endif
                                        </p>
                                        <span class="rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider ring-1 {{ $hij['type'] === 'hirman' ? 'bg-red-600 text-white ring-red-600' : 'bg-gold-soft text-gold-deep ring-gold/40' }}">{{ $hij['type_label'] }}</span>
                                    </div>
                                    <p class="mt-1 text-[11px] font-semibold text-red-800">{{ $hij['cause_label'] }}</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-ink/70">{{ $hij['reason'] }}</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-ink/60">{{ $hijabEntry['reason'] }}</p>
                                    <p class="mt-1 text-[11px] font-bold {{ $hij['type'] === 'hirman' ? 'text-red-800' : 'text-gold-deep' }}">
                                        Status akhir: {{ $hij['type'] === 'hirman' ? 'terhalang seluruhnya' : 'bagian berkurang' }}
                                        @if ($hij['final_share'])
                                            · bagian akhir {{ $hij['final_share'] }}
                                        @endif
                                    </p>
                                    @if ($hij['type'] === 'nuqshan' && $hijabEntry['amount_text'])
                                        <p class="tnum mt-0.5 text-sm font-extrabold text-emerald-900">
                                            {{ $hijabEntry['amount_text'] }}
                                            @if ($hijabEntry['per_person_amount_text'])
                                                <span class="text-[11px] font-bold text-ink/55">· per orang {{ $hijabEntry['per_person_amount_text'] }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="submit" class="u-btn-emerald px-7 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16 2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3Z"/></svg>
                        Hitung Sekarang
                    </button>
                    <button type="button" x-on:click="$dispatch('open-reset-modal', { mode: 'faraid' })"
                        class="rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-semibold text-emerald-900 transition hover:bg-emerald-900/5">
                        Mulai Perhitungan Baru
                    </button>
                </div>

                <div wire:loading wire:target="calculate" class="mt-3 text-sm font-medium text-emerald-900">
                    Menghitung…
                </div>
            </fieldset>
        </form>

        {{-- ===== Hasil ===== --}}
        <section class="mt-8" aria-live="polite">
            @if (session()->has('message'))
                <p class="mb-4 rounded-xl bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-900 ring-1 ring-emerald-200">
                    {{ session('message') }}
                </p>
            @endif

            @if ($showResult && $result)
                <div class="mb-4 flex flex-wrap gap-3">
                    <button type="button" wire:click="backToInput"
                        class="u-choice px-5 py-2.5 text-sm">
                        ← Kembali mengubah input
                    </button>
                    <button type="button" x-on:click="$dispatch('open-reset-modal', { mode: 'faraid' })"
                        class="u-choice px-5 py-2.5 text-sm">
                        + Mulai perhitungan baru
                    </button>
                </div>
                @php $netCents = (int) round($result['net_estate'] * 100); @endphp

                @if ($result['status'] === 'valid')
                    <div class="u-smart-card overflow-hidden">
                        <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-700 px-5 py-3.5">
                            <h2 class="u-display text-lg text-ivory">Hasil Pembagian</h2>
                            <span class="u-chip">DEMO — verifikasi ahli faraid</span>
                        </div>

                        <div class="p-5">
                            <p class="text-xs text-ink/60">
                                Metode: <strong class="text-ink">{{ $result['method_label'] }}</strong>
                                · Versi mesin: v{{ $result['engine_version'] }}
                            </p>

                            {{-- Banner ringkasan: harta awal → kewajiban → wasiat → harta bersih --}}
                            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-5">
                                <div class="rounded-xl bg-surface p-3.5 ring-1 ring-emerald-900/5">
                                    <dt class="text-xs text-ink/55">Harta awal</dt>
                                    <dd class="tnum font-bold text-emerald-900">Rp {{ number_format($result['inputs']['estate'], 2, ',', '.') }}</dd>
                                </div>
                                <div class="rounded-xl bg-surface p-3.5 ring-1 ring-emerald-900/5">
                                    <dt class="text-xs text-ink/55">Kewajiban (jenazah + hutang)</dt>
                                    <dd class="tnum font-bold text-emerald-900">
                                        Rp {{ number_format($result['inputs']['funeral'] + $result['inputs']['debts'], 2, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="rounded-xl bg-surface p-3.5 ring-1 ring-gold/30">
                                    <div class="flex items-start justify-between gap-1.5">
                                        <dt class="text-xs text-ink/55">Wasiat</dt>
                                        <span class="shrink-0 rounded-full bg-gold-soft/70 px-1.5 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-gold-deep ring-1 ring-gold/40">Wasiat · maks ⅓</span>
                                    </div>
                                    <dd class="tnum font-bold text-emerald-900">Rp {{ number_format($result['inputs']['bequest'], 2, ',', '.') }}</dd>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-3.5 ring-1 ring-emerald-200">
                                    <dt class="text-xs text-emerald-800/70">Harta bersih dibagi</dt>
                                    <dd class="tnum font-bold text-emerald-900">Rp {{ number_format($result['net_estate'], 2, ',', '.') }}</dd>
                                </div>
                                <div class="rounded-xl p-3.5 ring-1 {{ $result['total_cents'] === $netCents ? 'bg-emerald-50 ring-emerald-200' : 'bg-red-50 ring-red-200' }}">
                                    <dt class="text-xs text-ink/55">Konsistensi total</dt>
                                    <dd class="font-bold {{ $result['total_cents'] === $netCents ? 'text-emerald-800' : 'text-red-700' }}">
                                        {{ $result['total_cents'] === $netCents ? '✓ konsisten' : '✗ tidak konsisten' }}
                                    </dd>
                                </div>
                            </dl>

                            {{-- ===== Visual Result Banner: donut chart interaktif + kartu rincian berbadge ===== --}}
                            @php
                                $palette = ['#064E3B', '#047857', '#10B981', '#34D399', '#6EE7B7', '#F59E0B', '#D97706', '#0F766E', '#115E59', '#059669'];
                                $faraidCodes = collect($result['shares'])->pluck('code')->all();
                                $lawStatus = static function (array $share) use ($faraidCodes): string {
                                    return match (true) {
                                        in_array($share['code'], ['son', 'grandson', 'brother'], true) => 'Asabah',
                                        $share['code'] === 'grandfather' => 'Asabah · pengganti ayah',
                                        $share['code'] === 'daughter' && in_array('son', $faraidCodes, true) => 'Asabah · 1:2',
                                        $share['code'] === 'granddaughter' && in_array('grandson', $faraidCodes, true) => 'Asabah · 1:2',
                                        $share['code'] === 'sister' && (in_array('brother', $faraidCodes, true) || in_array('daughter', $faraidCodes, true)) => 'Asabah · 1:2',
                                        $share['code'] === 'father' && ! in_array('son', $faraidCodes, true) => 'Asabah',
                                        default => 'Dzawil Furudh',
                                    };
                                };
                                $donutSlices = [];
                                $cumulative = 0.0;
                                foreach (collect($result['shares'])->values() as $sliceIndex => $sliceShare) {
                                    $slicePct = max(0.0, min(100.0, (float) $sliceShare['percent']));
                                    $donutSlices[] = [
                                        'label' => $sliceShare['name'].($sliceShare['count'] > 1 ? ' (×'.$sliceShare['count'].')' : ''),
                                        'value' => $sliceShare['total_cents'] / 100,
                                        'pct' => $slicePct,
                                        'color' => $palette[$sliceIndex % count($palette)],
                                        'dash' => $slicePct.' '.max(0.0, 100 - $slicePct),
                                        'offset' => 25 - $cumulative,
                                    ];
                                    $cumulative += $slicePct;
                                }
                            @endphp
                            <div class="mt-6 grid gap-6 lg:grid-cols-[auto_1fr] lg:items-center" x-data='donutChart(@json($donutSlices))'>
                                {{-- Donut chart — hover segmen/sinkron dengan kartu --}}
                                <div class="mx-auto lg:mx-0">
                                    <div class="relative h-44 w-44 md:h-52 md:w-52">
                                        <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90" role="img" aria-label="Diagram donat pembagian harta bersih">
                                            <circle cx="21" cy="21" r="15.9155" fill="none" stroke="#F1F5F9" stroke-width="5.5"/>
                                            @foreach ($donutSlices as $donutIndex => $donutSlice)
                                                <circle cx="21" cy="21" r="15.9155" fill="none"
                                                    class="u-donut-seg"
                                                    stroke="{{ $donutSlice['color'] }}"
                                                    stroke-dasharray="{{ $donutSlice['dash'] }}"
                                                    stroke-dashoffset="{{ $donutSlice['offset'] }}"
                                                    x-on:mouseenter="hovered = {{ $donutIndex }}"
                                                    x-on:mouseleave="hovered = null"
                                                    :stroke-width="hovered === {{ $donutIndex }} ? 7 : 5.5"
                                                    :opacity="hovered === null || hovered === {{ $donutIndex }} ? 1 : 0.3"/>
                                            @endforeach
                                        </svg>
                                        <div class="pointer-events-none absolute inset-0 grid place-items-center text-center">
                                            <div>
                                                <p class="text-[9px] font-extrabold uppercase tracking-wider text-ink/45" x-text="centerLabel"></p>
                                                <p class="tnum text-lg font-extrabold text-emerald-900 md:text-xl" x-text="money(centerValue)"></p>
                                                <p class="tnum text-[10px] font-semibold text-ink/50" x-text="percent(centerPct)"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-center text-[10px] text-ink/40">Sentuh/arahkan ke bagian untuk rincian</p>
                                </div>

                                {{-- Summary cards — %, Rp, badge status hukum --}}
                                <div class="grid gap-2.5 sm:grid-cols-2">
                                    @foreach ($result['shares'] as $cardIndex => $cardShare)
                                        @php
                                            $cardStatus = $lawStatus($cardShare);
                                            $cardIsAsabah = str_starts_with($cardStatus, 'Asabah');
                                        @endphp
                                        <div
                                            class="u-smart-card flex flex-wrap items-start justify-between gap-2 p-3.5"
                                            x-on:mouseenter="hovered = {{ $cardIndex }}"
                                            x-on:mouseleave="hovered = null"
                                            :class="hovered !== null && hovered !== {{ $cardIndex }} ? 'opacity-50' : ''">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-emerald-900">
                                                    {{ $cardShare['name'] }}
                                                    @if ($cardShare['count'] > 1)
                                                        <span class="text-xs text-ink/55">(×{{ $cardShare['count'] }})</span>
                                                    @endif
                                                </p>
                                                @if ($cardShare['code'] === 'wife' && $cardShare['count'] > 1)
                                                    <p class="mt-0.5 text-[11px] text-ink/60">Bagian kolektif <strong>seluruh</strong> istri — dibagi rata, bukan {{ $cardShare['text'] }} per istri.</p>
                                                @endif
                                                <p class="tnum mt-0.5 text-xs font-semibold text-emerald-900/85">
                                                    {{ $cardShare['text'] }}
                                                    @if ($cardShare['per_person_text'] !== null)
                                                        <span class="text-ink/45">· per orang {{ $cardShare['per_person_text'] }}</span>
                                                    @endif
                                                </p>
                                                <p class="tnum mt-1.5 text-lg font-extrabold tracking-tight text-emerald-900">
                                                    Rp {{ number_format($cardShare['total_cents'] / 100, 2, ',', '.') }}
                                                    <span class="text-[11px] font-bold text-ink/55">{{ number_format($cardShare['percent'], 4, ',', '.') }}%</span>
                                                </p>
                                            </div>
                                            <div class="flex shrink-0 flex-col items-end gap-1">
                                                <span class="rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider ring-1 {{ $cardIsAsabah ? 'bg-gold-soft/70 text-gold-deep ring-gold/40' : 'bg-emerald-soft text-emerald-900 ring-emerald-700/30' }}">{{ $cardStatus }}</span>
                                                @if ($cardShare['disputed'])
                                                    <span class="rounded bg-gold-soft px-1.5 py-0.5 text-[10px] font-bold text-gold-deep">beda pendapat</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Berhak --}}
                            <h3 class="mt-6 font-bold text-emerald-900">Ahli waris berhak</h3>
                            <div class="mt-2 overflow-x-auto rounded-xl ring-1 ring-emerald-900/10">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-emerald-900/10 bg-emerald-900/5 text-left text-xs uppercase tracking-wider text-emerald-900/60">
                                            <th class="px-4 py-2.5">Ahli waris</th>
                                            <th class="px-4 py-2.5">Pecahan</th>
                                            <th class="px-4 py-2.5 text-right">%</th>
                                            <th class="px-4 py-2.5 text-right">Total (Rp)</th>
                                            <th class="px-4 py-2.5 text-right">Per orang (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result['shares'] as $share)
                                            <tr class="border-b border-emerald-900/5 last:border-0 align-top transition hover:bg-emerald-900/3">
                                                <td class="px-4 py-2.5">
                                                    {{ $share['name'] }}
                                                    @if ($share['count'] > 1)
                                                        <span class="text-xs text-ink/55">(×{{ $share['count'] }})</span>
                                                    @endif
                                                    @if ($share['code'] === 'wife' && $share['count'] > 1)
                                                        <span class="block text-[10px] text-ink/40">bagian kolektif seluruh istri — dibagi rata</span>
                                                    @endif
                                                    @if ($share['disputed'])
                                                        <span class="ml-1 rounded bg-gold-soft px-1.5 py-0.5 text-[10px] font-bold text-gold-deep">beda pendapat</span>
                                                    @endif
                                                </td>
                                                <td class="tnum px-4 py-2.5 font-semibold text-emerald-900">
                                                    {{ $share['text'] }}
                                                    @if ($share['per_person_text'] !== null)
                                                        <span class="block text-[10px] text-ink/40">· per orang {{ $share['per_person_text'] }}</span>
                                                    @endif
                                                </td>
                                                <td class="tnum px-4 py-2.5 text-right text-xs text-ink/70">
                                                    {{ number_format($share['percent'], 4, ',', '.') }}%
                                                </td>
                                                <td class="tnum px-4 py-2.5 text-right font-semibold">Rp {{ number_format($share['total_cents'] / 100, 2, ',', '.') }}</td>
                                                <td class="tnum px-4 py-2.5 text-right text-xs font-semibold">Rp {{ number_format($share['per_person_cents'] / 100, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t-2 border-emerald-900/15 bg-emerald-900/5 font-bold text-emerald-900">
                                            <td class="px-4 py-2.5" colspan="3">Total = harta bersih</td>
                                            <td class="tnum px-4 py-2.5 text-right">Rp {{ number_format($result['total_cents'] / 100, 2, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Terhalang — kartu ringkasan berbadge Hijab · Mahjub --}}
                            @if ($result['blocked'] !== [])
                                <h3 class="mt-6 font-bold text-emerald-900">Ahli waris terhalang (hijab)</h3>
                                <div class="mt-2 grid gap-2.5 sm:grid-cols-2">
                                    @foreach ($result['blocked'] as $blocked)
                                        <div class="u-smart-card flex flex-wrap items-start justify-between gap-2 p-3.5">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-emerald-900">{{ $blocked['name'] }}</p>
                                                <p class="mt-0.5 text-xs text-ink/70">{{ $blocked['reason'] }}</p>
                                            </div>
                                            <span class="shrink-0 rounded-full bg-gold-soft/70 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-gold-deep ring-1 ring-gold/40">Hijab · Mahjub</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Langkah perhitungan --}}
                            <details class="mt-6 rounded-xl bg-surface px-4 py-3">
                                <summary class="cursor-pointer text-sm font-bold text-emerald-900">Langkah perhitungan (jejak audit)</summary>
                                <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm text-ink/80">
                                    @foreach ($result['trace'] as $step)
                                        <li class="pl-1">{{ $step }}</li>
                                    @endforeach
                                </ol>
                            </details>

                            {{-- Catatan + pembulatan --}}
                            <div class="mt-4 space-y-2 text-xs text-ink/80">
                                @foreach ($result['notes'] as $note)
                                    <p class="rounded-xl bg-emerald-50 p-3 ring-1 ring-emerald-100">ℹ️ {{ $note }}</p>
                                @endforeach
                            </div>

                            @if ($result['disputed'])
                                <p class="mt-3 rounded-xl border border-gold/40 bg-gold-soft/50 p-3 text-xs text-amber-900">
                                    ⚠️ Komposisi ini memuat <strong>beda pendapat fikih</strong>. Kelompok lain yang sah
                                    dapat menghasilkan angka berbeda.
                                </p>
                            @endif

                            {{-- Simpan ke riwayat --}}
                            <div class="mt-5 border-t border-emerald-900/10 pt-4">
                                <button type="button" wire:click="saveToHistory" wire:loading.attr="disabled"
                                    class="u-btn-gold px-6 py-2.5 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Simpan ke Riwayat
                                </button>
                                <p class="mt-2 text-xs text-ink/50">
                                    Riwayat disimpan anonim menggunakan sesi peramban ini dan bisa Anda hapus di halaman Riwayat.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-gold/40 bg-gold-soft/50 p-5 text-sm text-ink/80">
                        <p class="font-bold">
                            {{ $result['status'] === 'not_supported' ? 'Kombinasi belum didukung.' : 'Perhitungan tidak dapat dilanjutkan.' }}
                        </p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($result['notes'] as $note)
                                <li>{{ $note }}</li>
                            @endforeach
                        </ul>
                        @if (count($result['trace']) > 0)
                            <details class="mt-3">
                                <summary class="cursor-pointer text-xs font-semibold">Lihat jejak perhitungan</summary>
                                <ol class="mt-2 list-decimal space-y-1 pl-5 text-xs">
                                    @foreach ($result['trace'] as $step)
                                        <li>{{ $step }}</li>
                                    @endforeach
                                </ol>
                            </details>
                        @endif
                        <p class="mt-3 text-xs text-ink/60">
                            Versi mesin: v{{ $result['engine_version'] }}. Komposisi di luar cakupan ditolak secara sengaja
                            agar tidak menghasilkan angka yang menyesatkan — silakan lakukan hitung manual bersama ahli faraid.
                        </p>
                    </div>
                @endif
            @else
                <div class="u-smart-card p-8 text-center text-sm text-ink/55">
                    Isi form di atas lalu klik <strong class="text-emerald-900">Hitung Sekarang</strong>. Hasil akan muncul di sini.
                </div>
            @endif
        </section>

    @elseif ($tab === 'civil')
        {{-- =====================================================
             Panel mode Hukum Waris Perdata (Golongan I, model
             edukasi) — terpisah total dari mesin faraid utama.
             ===================================================== --}}
        <div class="mt-6">
            <form wire:submit="calculateCivil" class="space-y-5">
                <p class="rounded-2xl border border-gold/40 bg-gold-soft/50 p-4 text-xs leading-relaxed text-ink/80">
                    Mode <strong class="text-ink">opsional</strong> edukasi pembanding bagi penganut non-Muslim
                    (Buku II KUHPerdata). Model dipakai: <strong class="text-ink">Golongan I</strong> — bagian setara
                    pasangan &amp; anak (Pasal 852) serta penggantian tempat cucu per cabang (Pasal 853). Tab
                    <strong class="text-ink">Faraidh utama berjalan persis seperti semula</strong>, dan hasil mode
                    perdata ini <strong class="text-ink">tidak disimpan ke Riwayat</strong>.
                </p>

                <fieldset class="rounded-2xl border border-black/5 bg-surface/60 p-4 md:p-5">
                    <legend class="u-kicker px-1 text-emerald-900/70">1 · Harta &amp; kewajiban — kolom dipakai bersama tab Faraidh</legend>

                    <div class="grid gap-3 md:grid-cols-2">
                        {{-- Kartu harta dasar (mode perdata) --}}
                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"/><path d="M4 8v13h16V8"/><path d="M12 12v5"/><path d="M9 17h6"/></svg>
                                    </span>
                                    <label for="estate" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Total harta peninggalan</label>
                                </div>
                                <span class="u-chip">harta</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="estate" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('estate')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('estate'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('estate')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- 4 · Harta bersih indikator --}}
                        <div class="rounded-2xl border border-dashed border-gold/45 bg-gold-soft/50 p-4">
                            <div class="flex items-center gap-2.5">
                                <span class="u-step-badge">4</span>
                                <p class="text-[0.78rem] font-bold leading-snug text-emerald-900 md:text-sm">Harta bersih dibagi</p>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-ink/70">
                                Mode perdata memakai harta bersih (harta − hutang − biaya jenazah − wasiat)
                                <strong class="text-ink">tanpa pembatasan wasiat sepertiga</strong> versi faraid;
                                pembatasan toleransi lain diatur Buku II KUHPerdata.
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 grid gap-3 md:grid-cols-3">
                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 8v5m0 3h.01"/><circle cx="12" cy="12" r="9"/></svg>
                                    </span>
                                    <label for="funeral" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Biaya pengurusan jenazah</label>
                                </div>
                                <span class="u-step-badge">1</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="funeral" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('funeral')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('funeral'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('funeral')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M7 4h10l4 4h-4"/><path d="M17 20h-6"/><path d="M7 4v16h10V8"/><path d="M10 12h4M10 16h4"/></svg>
                                    </span>
                                    <label for="debts" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Hutang almarhum</label>
                                </div>
                                <span class="u-step-badge">2</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="debts" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('debts')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('debts'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('debts')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="u-smart-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="u-icon-box h-9 w-9 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                    </span>
                                    <label for="bequest" class="text-[0.78rem] font-bold leading-snug text-ink md:text-sm">Wasiat/hibah</label>
                                </div>
                                <span class="u-step-badge">3</span>
                            </div>
                            <div class="relative mt-3">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-[0.8rem] font-extrabold text-emerald-900/55 tnum">Rp</span>
                                <input id="bequest" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                                    x-data="rupiahInput('bequest')"
                                    x-on:input="write($event)"
                                    x-effect="sync($wire.get('bequest'))"
                                    class="u-input tnum py-2.5 pl-11 pr-3 text-sm font-bold tracking-wide md:text-base">
                            </div>
                            @error('bequest')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="rounded-2xl border border-black/5 bg-surface/60 p-4 md:p-5">
                    <legend class="u-kicker px-1 text-emerald-900/70">2 · Ahli waris Golongan I</legend>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        {{-- Toggle pasangan hidup terlama --}}
                        <button type="button"
                            x-on:click="$wire.toggle('civilSpouse')"
                            :class="$wire.get('civilSpouse') ? 'u-choice u-choice-active px-4 py-3' : 'u-choice px-4 py-3 text-ink/70'"
                            :aria-pressed="$wire.get('civilSpouse') ? 'true' : 'false'">
                            <span class="u-icon-box h-9 w-9 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="8" cy="7.5" r="3"/><path d="M2.5 20a5.5 5.5 0 0 1 11 0"/><circle cx="17" cy="7.5" r="3"/><path d="M11.5 20a5.5 5.5 0 0 1 11 0"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs font-bold leading-tight">Pasangan hidup terlama</span>
                                <span class="block text-[10px] font-medium text-ink/45">diperlakukan seolah anak sah · Pasal 852</span>
                            </span>
                        </button>

                        {{-- Counter: anak sah --}}
                        <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="u-icon-box h-9 w-9 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="6.5" r="2.8"/><path d="M6.5 20a5.5 5.5 0 0 1 11 0"/></svg>
                                </span>
                                <label for="civilChildren" class="text-xs font-bold leading-tight text-ink">Anak sah</label>
                            </div>
                            <div class="ml-auto flex items-center gap-1.5">
                                <button type="button" class="u-step-btn" aria-label="Kurangi anak sah" x-on:click="$wire.set('civilChildren', Math.max(0, $wire.get('civilChildren') - 1))">−</button>
                                <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('civilChildren')"></span>
                                <button type="button" class="u-step-btn" aria-label="Tambah anak sah" x-on:click="$wire.set('civilChildren', Math.min(30, $wire.get('civilChildren') + 1))">+</button>
                            </div>
                            @error('civilChildren')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Counter: cabang --}}
                        <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="u-icon-box h-9 w-9 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 21V9"/><path d="M12 9 7 4"/><path d="M12 9l5-5"/><path d="M6 13H2M22 11h-4"/></svg>
                                </span>
                                <label for="civilBranches" class="text-xs font-bold leading-tight text-ink">Cabang anak<br class="hidden lg:block"> yang wafat</label>
                            </div>
                            <div class="ml-auto flex items-center gap-1.5">
                                <button type="button" class="u-step-btn" aria-label="Kurangi cabang anak wafat" x-on:click="$wire.set('civilBranches', Math.max(0, $wire.get('civilBranches') - 1))">−</button>
                                <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('civilBranches')"></span>
                                <button type="button" class="u-step-btn" aria-label="Tambah cabang anak wafat" x-on:click="$wire.set('civilBranches', Math.min(30, $wire.get('civilBranches') + 1))">+</button>
                            </div>
                            @error('civilBranches')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Counter: total cucu --}}
                        <div class="u-smart-card flex flex-wrap items-center justify-between gap-2 p-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="u-icon-box h-9 w-9 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><circle cx="5" cy="6" r="2.4"/><circle cx="12" cy="6" r="2.4"/><circle cx="19" cy="6" r="2.4"/><path d="M2.5 20a5 5 0 0 1 9.5-2M22.5 20a5 5 0 0 0-9.5-2M2.5 15h19"/></svg>
                                </span>
                                <label for="civilGrandchildren" class="text-xs font-bold leading-tight text-ink">Total cucu<br class="hidden lg:block"> (semua cabang)</label>
                            </div>
                            <div class="ml-auto flex items-center gap-1.5">
                                <button type="button" class="u-step-btn" aria-label="Kurangi total cucu" x-on:click="$wire.set('civilGrandchildren', Math.max(0, $wire.get('civilGrandchildren') - 1))">−</button>
                                <span class="tnum min-w-8 text-center text-lg font-extrabold text-emerald-900" aria-live="polite" x-text="$wire.get('civilGrandchildren')"></span>
                                <button type="button" class="u-step-btn" aria-label="Tambah total cucu" x-on:click="$wire.set('civilGrandchildren', Math.min(99, $wire.get('civilGrandchildren') + 1))">+</button>
                            </div>
                            @error('civilGrandchildren')<p class="w-full text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <p class="mt-3 rounded-xl bg-white px-4 py-2.5 text-xs leading-relaxed text-ink/70 ring-1 ring-black/5">
                        Berbeda dengan faraid: bagian anak laki-laki dan anak perempuan <strong class="text-ink">SAMA BESAR</strong>
                        (Pasal 852). Anak yang lebih dulu wafat digantikan cucu-cucunya <strong class="text-ink">satu bagian per
                        cabang</strong>, dan cucu dalam cabang membagi sama rata (Pasal 853).
                    </p>
                </fieldset>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="u-btn-emerald px-7 py-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path stroke-linecap="round" d="M7 21h10M12 3v18"/></svg>
                        Hitung (Mode Perdata)
                    </button>
                    <button type="button" x-on:click="$dispatch('open-reset-modal', { mode: 'civil' })"
                        class="rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-semibold text-emerald-900 transition hover:bg-emerald-900/5">
                        Mulai Perhitungan Baru
                    </button>
                </div>

                <div wire:loading wire:target="calculateCivil" class="text-sm font-medium text-emerald-900">
                    Menghitung…
                </div>
            </form>

            <section class="mt-8" aria-live="polite">
                @if ($showCivilResult && $civilResult)
                    @if ($civilResult['status'] === 'valid')
                        <div class="u-smart-card overflow-hidden">
                            <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-700 px-5 py-3.5">
                                <h2 class="u-display text-lg text-ivory">Hasil Pembagian Perdata — Golongan I</h2>
                                <span class="u-chip">DEMO — bukan nasihat hukum</span>
                            </div>

                            <div class="p-5">
                                <p class="text-xs text-ink/60">
                                    Basis: KUHPerdata Pasal 852 (bagian setara) &amp; Pasal 853 (penggantian tempat)
                                    · {{ $civilResult['parts'] }} satuan bagian.
                                </p>

                                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                                    <div class="rounded-xl bg-emerald-50 p-3.5 ring-1 ring-emerald-200">
                                        <dt class="text-xs text-emerald-800/70">Harta bersih dibagi</dt>
                                        <dd class="tnum font-bold text-emerald-900">Rp {{ number_format($civilResult['net_cents'] / 100, 2, ',', '.') }}</dd>
                                    </div>
                                    <div class="rounded-xl bg-gold-soft/60 p-3.5 text-xs text-amber-900 ring-1 ring-gold/30">
                                        <dt class="font-bold">Tertib penyimpanan</dt>
                                        <dd class="mt-1">Hasil mode perdata <strong>tidak disimpan ke Riwayat</strong> — simpan-riwayat hanya untuk mode Faraidh utama.</dd>
                                    </div>
                                </dl>

                                {{-- Visual Result Banner mode perdata: donut + kartu berbadge --}}
                                @php
                                    $civilPalette = ['#064E3B', '#047857', '#10B981', '#34D399', '#6EE7B7', '#F59E0B', '#D97706', '#0F766E', '#115E59', '#059669'];
                                    $civilSlices = [];
                                    $civilCumulative = 0.0;
                                    foreach (collect($civilResult['entries'])->values() as $civilIndex => $civilEntry) {
                                        $civilPct = $civilResult['net_cents'] > 0
                                            ? max(0.0, min(100.0, $civilEntry['total_cents'] / $civilResult['net_cents'] * 100.0))
                                            : 0.0;
                                        $civilSlices[] = [
                                            'label' => $civilEntry['name'].($civilEntry['count'] > 1 ? ' (×'.$civilEntry['count'].')' : ''),
                                            'value' => $civilEntry['total_cents'] / 100,
                                            'pct' => $civilPct,
                                            'color' => $civilPalette[$civilIndex % count($civilPalette)],
                                            'dash' => $civilPct.' '.max(0.0, 100 - $civilPct),
                                            'offset' => 25 - $civilCumulative,
                                        ];
                                        $civilCumulative += $civilPct;
                                    }
                                @endphp
                                <div class="mt-6 grid gap-6 lg:grid-cols-[auto_1fr] lg:items-center" x-data='donutChart(@json($civilSlices))'>
                                    <div class="mx-auto lg:mx-0">
                                        <div class="relative h-40 w-40 md:h-48 md:w-48">
                                            <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90" role="img" aria-label="Diagram donat pembagian harta bersih mode perdata">
                                                <circle cx="21" cy="21" r="15.9155" fill="none" stroke="#F1F5F9" stroke-width="5.5"/>
                                                @foreach ($civilSlices as $civilDonutIndex => $civilDonutSlice)
                                                    <circle cx="21" cy="21" r="15.9155" fill="none"
                                                        class="u-donut-seg"
                                                        stroke="{{ $civilDonutSlice['color'] }}"
                                                        stroke-dasharray="{{ $civilDonutSlice['dash'] }}"
                                                        stroke-dashoffset="{{ $civilDonutSlice['offset'] }}"
                                                        x-on:mouseenter="hovered = {{ $civilDonutIndex }}"
                                                        x-on:mouseleave="hovered = null"
                                                        :stroke-width="hovered === {{ $civilDonutIndex }} ? 7 : 5.5"
                                                        :opacity="hovered === null || hovered === {{ $civilDonutIndex }} ? 1 : 0.3"/>
                                                @endforeach
                                            </svg>
                                            <div class="pointer-events-none absolute inset-0 grid place-items-center text-center">
                                                <div>
                                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-ink/45" x-text="centerLabel"></p>
                                                    <p class="tnum text-lg font-extrabold text-emerald-900" x-text="money(centerValue)"></p>
                                                    <p class="tnum text-[10px] font-semibold text-ink/50" x-text="percent(centerPct)"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-center text-[10px] text-ink/40">Sentuh/arahkan ke bagian untuk rincian</p>
                                    </div>

                                    <div class="grid gap-2.5 sm:grid-cols-2">
                                        @foreach ($civilResult['entries'] as $civilCardIndex => $civilCardEntry)
                                            @php
                                                $civilIsPengganti = str_contains((string) $civilCardEntry['rujukan'], 'penggantian tempat');
                                                $civilCardPct = $civilResult['net_cents'] > 0
                                                    ? $civilCardEntry['total_cents'] / $civilResult['net_cents'] * 100.0
                                                    : 0.0;
                                            @endphp
                                            <div
                                                class="u-smart-card flex flex-wrap items-start justify-between gap-2 p-3.5"
                                                x-on:mouseenter="hovered = {{ $civilCardIndex }}"
                                                x-on:mouseleave="hovered = null"
                                                :class="hovered !== null && hovered !== {{ $civilCardIndex }} ? 'opacity-50' : ''">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-emerald-900">
                                                        {{ $civilCardEntry['name'] }}
                                                        @if ($civilCardEntry['count'] > 1)
                                                            <span class="text-xs text-ink/55">(×{{ $civilCardEntry['count'] }})</span>
                                                        @endif
                                                    </p>
                                                    <p class="mt-0.5 text-xs text-ink/70">{{ $civilCardEntry['rujukan'] }}</p>
                                                    <p class="tnum mt-1.5 text-lg font-extrabold tracking-tight text-emerald-900">
                                                        Rp {{ number_format($civilCardEntry['total_cents'] / 100, 2, ',', '.') }}
                                                        <span class="text-[11px] font-bold text-ink/55">{{ number_format($civilCardPct, 4, ',', '.') }}%</span>
                                                    </p>
                                                    <p class="tnum text-[11px] font-semibold text-ink/55">Per orang Rp {{ number_format($civilCardEntry['per_person_cents'] / 100, 2, ',', '.') }}</p>
                                                </div>
                                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider ring-1 {{ $civilIsPengganti ? 'bg-gold-soft/70 text-gold-deep ring-gold/40' : 'bg-emerald-soft text-emerald-900 ring-emerald-700/30' }}">
                                                    {{ $civilIsPengganti ? 'Ahli Waris Pengganti · 853' : 'Bagian Setara · 852' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <h3 class="mt-6 font-bold text-emerald-900">Pembagian</h3>
                                <div class="mt-2 overflow-x-auto rounded-xl ring-1 ring-emerald-900/10">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-emerald-900/10 bg-emerald-900/5 text-left text-xs uppercase tracking-wider text-emerald-900/60">
                                                <th class="px-4 py-2.5">Ahli waris</th>
                                                <th class="px-4 py-2.5">Pecahan</th>
                                                <th class="px-4 py-2.5 text-right">Total (Rp)</th>
                                                <th class="px-4 py-2.5 text-right">Per orang (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($civilResult['entries'] as $entry)
                                                <tr class="border-b border-emerald-900/5 last:border-0 align-top transition hover:bg-emerald-900/3">
                                                    <td class="px-4 py-2.5">
                                                        {{ $entry['name'] }}
                                                        @if ($entry['count'] > 1)
                                                            <span class="text-xs text-ink/55">(×{{ $entry['count'] }})</span>
                                                        @endif
                                                        <span class="block text-[10px] text-ink/40">{{ $entry['rujukan'] }}</span>
                                                        @if (! empty($entry['catatan']))
                                                            <span class="mt-0.5 block text-[10px] italic text-ink/50">{{ $entry['catatan'] }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="tnum px-4 py-2.5 font-semibold text-emerald-900">{{ $entry['unit_text'] }}</td>
                                                    <td class="tnum px-4 py-2.5 text-right font-semibold">Rp {{ number_format($entry['total_cents'] / 100, 2, ',', '.') }}</td>
                                                    <td class="tnum px-4 py-2.5 text-right text-xs font-semibold">Rp {{ number_format($entry['per_person_cents'] / 100, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="border-t-2 border-emerald-900/15 bg-emerald-900/5 font-bold text-emerald-900">
                                                <td class="px-4 py-2.5" colspan="2">Total = harta bersih</td>
                                                <td class="tnum px-4 py-2.5 text-right">Rp {{ number_format($civilResult['total_cents'] / 100, 2, ',', '.') }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="mt-4 space-y-2 text-xs text-ink/80">
                                    @foreach ($civilResult['notes'] as $note)
                                        <p class="rounded-xl bg-emerald-50 p-3 ring-1 ring-emerald-100">ℹ️ {{ $note }}</p>
                                    @endforeach
                                </div>

                                <h3 class="mt-6 text-sm font-bold text-emerald-900">Pasal KUHPerdata yang dipakai model ini</h3>
                                <ul class="mt-2 space-y-2">
                                    @foreach ($civilResult['pasals'] as $pasal)
                                        <li class="u-pasal-card p-3.5">
                                            <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-900">{{ $pasal['label'] }}</p>
                                            <p class="mt-1 text-sm text-ink/80">{{ $pasal['teks'] }}</p>
                                            @if (! empty($pasal['catatan']))
                                                <p class="mt-1 text-[10px] italic text-ink/50">{{ $pasal['catatan'] }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                @if (! empty($kuhperdata['golongan']))
                                    <details class="mt-3 rounded-xl bg-surface px-4 py-3">
                                        <summary class="cursor-pointer text-xs font-bold text-emerald-900">Penggolongan ahli waris KUHPerdata (Golongan I–IV)</summary>
                                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-ink/80">
                                            @foreach ($kuhperdata['golongan'] as $golongan)
                                                <li>{{ $golongan }}</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @endif

                                <p class="mt-4 rounded-xl border border-gold/40 bg-gold-soft/50 p-3 text-xs text-amber-900">
                                    ⚠️ Hasil sengaja hanya mencakup <strong>Golongan I</strong> (Pasal 852). Kasus nyata
                                    bisa menyangkut Golongan II–IV, perkawinan kedua (Pasal 852 huruf b), atau klausul
                                    lain — serahkan kepada notaris/ahli hukum.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-gold/40 bg-gold-soft/50 p-5 text-sm text-ink/80">
                            <p class="font-bold">Perhitungan mode perdata tidak dapat dilanjutkan.</p>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($civilResult['notes'] as $note)
                                    <li>{{ $note }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @else
                    <div class="u-smart-card p-8 text-center text-sm text-ink/55">
                        Isi form di atas lalu klik <strong class="text-emerald-900">Hitung (Mode Perdata)</strong>. Simulasi Golongan I akan muncul di sini.
                    </div>
                @endif
            </section>
        </div>

    @elseif ($tab === 'kontemporer')
        {{-- =====================================================
             Panel Kontemporer: yurisprudensi MA RI & topik
             pembanding — murni edukasi referensial.
             ===================================================== --}}
        <div class="mt-6 space-y-4">
            <div class="u-smart-card overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-700 px-5 py-3.5">
                    <h2 class="u-display text-lg text-ivory">Topik Kontemporer — peta rujukan</h2>
                </div>
                <p class="px-5 py-4 text-sm leading-relaxed text-ink/70">
                    Rangkuman topik hukum waris yang berkembang di praktik, putusan Mahkamah Agung RI, dan
                    perbandingan KHI dengan KUHPerdata. Murni edukasi referensial — <strong class="text-ink">tidak memengaruhi
                    mesin hitung faraid sama sekali</strong>.
                </p>
            </div>

            @foreach ($kontemporer as $card)
                <article class="u-smart-card overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start gap-3">
                            <span class="u-icon-box h-10 w-10 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
                            </span>
                            <h3 class="font-bold leading-snug text-emerald-900">{{ $card['judul'] }}</h3>
                        </div>
                        <p class="mt-2.5 text-sm leading-relaxed text-ink/80">{{ $card['ringkas'] }}</p>

                        @if (! empty($card['rujukan']))
                            <p class="u-sec-title mt-4">Rujukan</p>
                            <ul class="mt-1.5 list-inside list-disc space-y-1 text-sm text-ink/80">
                                @foreach ($card['rujukan'] as $rujukan)
                                    <li>{{ $rujukan }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (! empty($card['jurnal_entries']))
                            <p class="u-sec-title mt-4">Baca Jurnal Ilmiah Terkait</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($card['jurnal_entries'] as $jurnal)
                                    <a href="{{ $jurnal['href'] }}" target="_blank" rel="noopener noreferrer" class="u-ref-btn">
                                        {{ $jurnal['label'] }}
                                        <span class="rounded bg-gold/20 px-1.5 py-0.5 text-[10px] font-extrabold text-gold-soft">
                                            {{ $jurnal['tag'] ?? 'Jurnal' }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach

            <p class="rounded-2xl bg-gold-soft/50 p-4 text-xs leading-relaxed text-amber-900 ring-1 ring-gold/30">
                Dalil shahih, pasal KHI &amp; KUHPerdata, dan yurisprudensi (antara lain MA No. 16 K/AG/2010)
                tersedia pada modal <strong>Lihat Detail</strong> halaman Ahli Waris dan Bagian Warisan, serta
                halaman <a href="{{ route('how-to') }}" class="font-semibold text-gold-deep underline">Cara Menghitung</a>.
            </p>
        </div>
    @endif

    {{-- Modal konfirmasi sebelum reset — input tidak dihapus jika memilih Batal --}}
    <div x-data="{ open: false, mode: 'faraid' }"
        @open-reset-modal.window="mode = $event.detail.mode || 'faraid'; open = true"
        @keydown.escape.window="open = false">
        <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none" aria-hidden="true">
            <div class="absolute inset-0 bg-emerald-950/50 backdrop-blur-sm"
                x-on:click="open = false" aria-hidden="true"></div>
            <div x-show="open" x-transition role="dialog" aria-modal="true"
                aria-labelledby="reset-modal-title"
                class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl ring-1 ring-emerald-950/10">
                <h3 id="reset-modal-title" class="text-lg font-bold text-ink">
                    Mulai perhitungan baru?
                </h3>
                <p class="mt-2 text-sm leading-relaxed text-ink/70">
                    Apakah kamu yakin ingin menghapus semua data dan memulai perhitungan baru?
                    Semua input yang sudah kamu isi <strong class="text-ink">akan dihapus</strong>.
                </p>
                <div class="mt-5 flex flex-wrap justify-end gap-3">
                    <button type="button" x-on:click="open = false"
                        class="rounded-full border border-black/10 bg-white px-5 py-2.5 text-sm font-semibold text-emerald-900 transition hover:bg-emerald-900/5">
                        Batal
                    </button>
                    <button type="button"
                        x-on:click="mode === 'civil' ? $wire.startNewCivil() : $wire.startNew(); open = false"
                        class="u-btn-emerald px-5 py-2.5 text-sm" autofocus>
                        Ya, Mulai Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
