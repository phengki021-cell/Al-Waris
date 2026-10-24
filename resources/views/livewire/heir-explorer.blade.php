<div x-data @keydown.escape.window="$wire.closeDetail()">
    <section>
        <p class="u-kicker text-gold-deep">Faraidh</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Ahli Waris</h1>
        <p class="mt-2 text-sm text-ink/70">
            Golongan ahli waris menurut ilmu faraid: dzawil furudh, ashabah, dan dzawil arham.
            Klik kartu atau tombol &laquo;Lihat Detail&raquo; untuk definisi, syarat, kondisi
            mahrum/hijab, serta dalil Al-Qur'an (Arab, Latin, Terjemahan Kemenag) dan pasal KHI.
        </p>
    </section>

    {{-- ===== MATERI: ASHABAH TIGA JENIS ===== --}}
    <section class="u-smart-card mt-8 p-5 md:p-6" aria-labelledby="asabah-title">
        <p class="u-kicker text-gold-deep">Materi</p>
        <h3 id="asabah-title" class="mt-2 text-lg font-bold">Ashabah: Tiga Jenis Penerima Sisa</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/70">
            Ashabah adalah golongan yang menerima <strong>sisa harta</strong> setelah bagian pasti
            (dzawil furudh) terbayar. Dalam ilmu faraid, ashabah terbagi tiga jenis:
        </p>
        <ol class="mt-4 space-y-3 text-sm leading-relaxed">
            <li class="rounded-xl bg-paper p-3 ring-1 ring-emerald-900/10">
                <p class="font-semibold text-ink">1. Ashabah bin nafsihi (bil nafsi)</p>
                <p class="mt-1 text-ink/80">
                    Menerima sisa karena kedudukan dirinya sendiri. Contoh:
                    <strong>anak laki-laki</strong>, <strong>ayah</strong>,
                    <strong>saudara sedarah laki-laki</strong>, saudara seayah laki-laki,
                    dan paman seayah.
                </p>
            </li>
            <li class="rounded-xl bg-paper p-3 ring-1 ring-emerald-900/10">
                <p class="font-semibold text-ink">2. Ashabah bil ghairihi (bil ghair)</p>
                <p class="mt-1 text-ink/80">
                    Menerima sisa karena <strong>menyertai</strong> orang lain yang menjadi sebabnya.
                    Contoh: <strong>putu laki-laki</strong> (cucu dari anak laki-laki) bersama putri
                    pewaris, <strong>anak perempuan</strong> bersama anak laki-laki (nisbah 2 : 1),
                    <strong>saudara perempuan sedarah/seayah</strong> bersama saudara
                    laki-laki mereka (nisbah 2 : 1), serta <strong>nenek</strong> bila bersama kakek
                    menurut ketentuan KHI.
                </p>
            </li>
            <li class="rounded-xl bg-paper p-3 ring-1 ring-emerald-900/10">
                <p class="font-semibold text-ink">3. Ashabah ma'al ghairi (ma'a al-ghair)</p>
                <p class="mt-1 text-ink/80">
                    Menerima sisa <strong>bersama</strong> orang yang bukan sebab sisa mereka.
                    Dalam KHI ini dikenal sebagai <strong>asabah bertakhsis</strong>: keluarga
                    dekat yang kedudukannya dirapatkan (disetarakan) dengan ashabah — antara lain
                    <strong>cucu perempuan</strong>, <strong>cucu laki-laki</strong> (saat tidak ada
                    anak laki-laki pewaris), <strong>kemenakan</strong>, dan <strong>sepupu</strong>.
                    Rincian bagiannya mengikuti ketentuan KHI.
                </p>
            </li>
        </ol>
    </section>

    {{-- ===== MATERI: HIJAB (HIRMANI, MAHJUB, NUQSHAN) ===== --}}
    <section class="u-smart-card mt-6 p-5 md:p-6" aria-labelledby="hijab-title">
        <p class="u-kicker text-gold-deep">Materi</p>
        <h3 id="hijab-title" class="mt-2 text-lg font-bold">Hijab &amp; Mahjub: Penghalang Warisan</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/70">
            <strong>Hijab</strong> berarti terhalangnya calon ahli waris karena kehadiran ahli waris
            yang lebih dekat/lebih berhak; yang terhalang disebut <strong>mahjub</strong>. Ada dua
            bentuk:
        </p>
        <ul class="mt-3 space-y-2 text-sm text-ink/80">
            <li class="rounded-xl bg-paper p-3 ring-1 ring-emerald-900/10">
                <strong>Hijab hirmani (total)</strong> — calon ahli waris sama sekali tidak menerima
                warisan (menjadi mahjub). Contoh klasik: ayah menghalangi kakek; anak laki-laki
                menghalangi saudara-saudara pewaris.
            </li>
            <li class="rounded-xl bg-paper p-3 ring-1 ring-emerald-900/10">
                <strong>Hijab nuqshan (pengurangan)</strong> — bagian ahli waris
                <em>berkurang</em>, bukan hilang. Contoh: suami 1/2 → 1/4 dan istri 1/4 → 1/8
                bila pewaris memiliki keturunan; ibu 1/3 → 1/6 bila ada keturunan atau
                saudara-saudara pewaris.
            </li>
        </ul>

        <h4 class="mt-4 text-sm font-semibold uppercase tracking-wide text-ink/60">
            Daftar penghalang utama (hijab hirmani)
        </h4>
        <div class="mt-2 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="bg-emerald-900/5 text-xs uppercase tracking-wider text-emerald-900/80">
                        <th class="px-3 py-2 font-semibold">Penghalang (hajib)</th>
                        <th class="px-3 py-2 font-semibold">Yang terhalang (mahjub)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-900/10">
                    <tr><td class="px-3 py-2 text-ink/90">Anak laki-laki</td><td class="px-3 py-2 text-ink/80">Semua saudara pewaris (sedarah, seayah, seibu) serta keturunannya, kemenakan, paman, dan sepupu</td></tr>
                    <tr><td class="px-3 py-2 text-ink/90">Putu laki-laki (cucu dari anak laki-laki)</td><td class="px-3 py-2 text-ink/80">Saudara-saudara pewaris beserta keturunannya, paman, dan sepupu</td></tr>
                    <tr><td class="px-3 py-2 text-ink/90">Ayah</td><td class="px-3 py-2 text-ink/80">Kakek, semua saudara pewaris beserta keturunannya, paman, dan sepupu</td></tr>
                    <tr><td class="px-3 py-2 text-ink/90">Ibu</td><td class="px-3 py-2 text-ink/80">Nenek (nenek lebih jauh terhalang nenek lebih dekat)</td></tr>
                    <tr><td class="px-3 py-2 text-ink/90">Saudara sedarah laki-laki</td><td class="px-3 py-2 text-ink/80">Saudara seayah (laki-laki &amp; perempuan) beserta keturunannya, paman, dan sepupu</td></tr>
                    <tr><td class="px-3 py-2 text-ink/90">Paman seayah</td><td class="px-3 py-2 text-ink/80">Sepupu (anak dari paman)</td></tr>
                </tbody>
            </table>
        </div>
        <p class="mt-3 text-xs text-ink/60">
            Catatan: pada fiqh klasik, dua saudara perempuan sedarah menghalangi satu saudara
            perempuan seayah; KHI memakai ketentuan kalalah tersendiri (QS. An-Nisaa: 176;
            KHI Pasal 182). Kewakilan cucu dari anak yang lebih dulu wafat diatur KHI Pasal 185.
        </p>
    </section>

    {{-- ===== MATERI: AHLI WARIS PENGGANTI ===== --}}
    <section class="u-smart-card mt-6 p-5 md:p-6" aria-labelledby="pengganti-title">
        <p class="u-kicker text-gold-deep">Materi</p>
        <h3 id="pengganti-title" class="mt-2 text-lg font-bold">Ahli Waris Pengganti</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/70">
            Yang dimaksud ahli waris pengganti adalah orang turunan sebab (perbudakan, wala Murahmat,
            atau perjanjian) yang menjadi <strong>ashabah bil ghair</strong>, yaitu:
        </p>
        <ul class="mt-3 space-y-1 text-sm text-ink/80">
            <li><strong>Mawla</strong> (pembebas budak atau yang diakafkan).</li>
            <li><strong>Anak dari mawla</strong> — keturunannya menggantikan ayahnya.</li>
            <li><strong>Dibelikan</strong>, yaitu seorang karib yang diangkat menjadi keturunan mawla.</li>
            <li><strong>Mu'at</strong>, yaitu orang yang dibebaskan karena sumpah.</li>
            <li><strong>Musafir al-Islam</strong>, yaitu pribumi yang turut berperang di jalan Allah, dan keturunannya.</li>
        </ul>
        <p class="mt-3 text-sm leading-relaxed text-ink/80">
            Semua ahli waris pengganti tidak dapat bagian waris kalau masih ada ahli waris
            golongan kerabat yang bersangkutan (faraid klasik). Sebaliknya, KHI mengadopsi kewakilan:
            <strong>cucu dari anak yang lebih dulu wafat menggantikan tempat ayah/ibunya</strong>
            (KHI Pasal 185), dan kerabat yang tidak memperoleh bagian ketika ada ahli waris kerabat
            golongan dekat dapat ditetapkan sebagai waris asabah bertakhsis (lihat penjelasan
            per-kartu di modal detail).
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
                        class="u-choice group w-full flex items-start justify-between gap-3 p-4 text-left"
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
                        <span class="mt-1 shrink-0 self-start rounded-lg border border-gold/60 bg-gold-soft/60 px-2.5 py-1 text-xs font-bold text-gold-deep transition group-hover:bg-gold group-hover:text-white">
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
                            <p class="u-kicker text-gold-soft">Profil Ahli Waris</p>
                            <p id="heir-detail-title" class="u-display mt-1.5 text-lg text-ivory md:text-xl">
                                Detail Ahli Waris — {{ $this->selectedHeir->name_id }}
                            </p>
                            <p class="mt-1.5 text-sm text-gold-soft/90">
                                {{ $this->selectedHeir->name_ar }}
                                {{ $this->selectedHeir->gender === 'm' ? '· Laki-laki' : '· Perempuan' }}
                            </p>
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
                    {{-- (a) Definisi & Pengertian --}}
                    <section>
                        <h2 class="u-sec-title">Definisi &amp; Pengertian</h2>
                        <p class="mt-2 rounded-xl bg-surface p-4 text-sm text-ink/80">{{ $this->selectedProfile['definisi'] }}</p>
                        <p class="mt-1 text-[10px] text-ink/40">Kategori: {{ $categories[$this->selectedHeir->category] ?? $this->selectedHeir->category }}</p>
                    </section>

                    {{-- (b) Syarat mendapatkan warisan --}}
                    <section>
                        <h2 class="u-sec-title">Syarat Mendapatkan Warisan</h2>
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
                        <h2 class="u-sec-title">Kondisi Mahrum / Hijab (Penghalang)</h2>
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
                        <h2 class="u-sec-title">Dalil Al-Qur'an &amp; KHI</h2>

                        @if (! empty($this->selectedProfile['verse']))
                            <p class="mt-2 inline-flex rounded bg-accent-soft px-2 py-0.5 text-xs font-semibold text-primary">
                                {{ $this->selectedProfile['verse']['label'] }}
                            </p>
                            <p dir="rtl" lang="ar" class="verse-arab u-verse-box mt-3 p-5 text-right text-emerald-950">
                                {{ $this->selectedProfile['verse']['arab'] }}
                            </p>
                            <p class="mt-3 rounded-xl bg-surface p-4 text-sm italic text-ink/80">
                                {{ $this->selectedProfile['verse']['latin'] }}
                                <span class="mt-2 block text-[10px] not-italic text-ink/40">
                                    Nota transliterasi Kemenag: tanda (a)/(un)/(in) adalah harakat akhir bacaan.
                                </span>
                            </p>
                            <div class="u-verse-terjemah mt-3 rounded-r-xl bg-surface p-4">
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

                        @if (! empty($this->selectedProfile['catatan_dalil']))
                            <p class="mt-2 rounded-xl border border-dashed border-black/10 bg-surface p-4 text-xs text-ink/70">
                                {{ $this->selectedProfile['catatan_dalil'] }}
                            </p>
                        @endif
                    </section>

                    {{-- Rapat: hadis shahih, KUHPerdata, yurisprudensi MA & jurnal ilmiah --}}
                    @include('partials.reference-blocks', [
                        'hadits' => $this->selectedProfile['hadits'],
                        'kuhperdata' => $this->selectedProfile['kuhperdata'],
                        'yurisprudensi' => $this->selectedProfile['yurisprudensi'],
                        'jurnal' => $this->selectedProfile['jurnal'],
                    ])
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
