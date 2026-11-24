<div class="mx-auto max-w-3xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Hasil Analisis Waris Perdata</h1>
        <p class="mt-2 text-sm text-ink/70">Analisis berdasarkan data keluarga yang tersimpan. Bukan keputusan pembagian final.</p>
    </section>

    <div class="u-smart-card p-5 md:p-6">
        <p class="text-sm text-ink/70">Pastikan data keluarga sudah diisi di <a href="{{ route('civil.family') }}" class="text-[#123D32] underline">Formulir Data Keluarga</a>.</p>
        <button type="button" wire:click="analyze" class="u-btn-gold mt-3 px-5 py-2.5 text-sm">Analisis Data</button>
    </div>

    @if (! empty($result))
        <div class="u-smart-card p-5 md:p-6 space-y-4">
            <div class="flex items-center gap-2">
                <span class="u-step-badge">{{ $result['status'] }}</span>
                <h2 class="text-lg font-bold text-ink">Hasil Analisis</h2>
            </div>

            @if (! empty($result['calonAhliWaris']))
                <div>
                    <p class="text-sm font-bold text-ink">Calon Ahli Waris</p>
                    <ul class="mt-2 space-y-1 text-sm text-ink/80">
                        @foreach ($result['calonAhliWaris'] as $c)
                            <li>{{ $c['nama'] }} — {{ $c['hubungan'] }} (Golongan {{ $c['golongan'] }}): {{ $c['alasan'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! empty($result['golongan']))
                <div>
                    <p class="text-sm font-bold text-ink">Golongan aktif</p>
                    <p class="text-sm text-ink/80">{{ $result['golongan']['aktif'] }}</p>
                </div>
            @endif

            @if (! empty($result['penggantianTempat']))
                <div>
                    <p class="text-sm font-bold text-ink">Penggantian Tempat</p>
                    <ul class="mt-2 space-y-1 text-sm text-ink/80">
                        @foreach ($result['penggantianTempat'] as $p)
                            <li>{{ $p['orang_yang_digantikan'] ?? '—' }}: {{ $p['catatan'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! empty($result['harta']))
                <div>
                    <p class="text-sm font-bold text-ink">Harta Peninggalan</p>
                    <p class="text-sm text-ink/80">{{ $result['harta']['catatan'] }}</p>
                </div>
            @endif

            @if (! empty($result['wasiat']))
                <div>
                    <p class="text-sm font-bold text-ink">Wasiat</p>
                    <p class="text-sm text-ink/80">{{ $result['wasiat']['catatan'] }}</p>
                </div>
            @endif

            @if (! empty($result['legitime']))
                <div>
                    <p class="text-sm font-bold text-ink">Legitime Portie</p>
                    <p class="text-sm text-ink/80">{{ $result['legitime']['catatan'] }}</p>
                </div>
            @endif

            @if (! empty($result['catatan']))
                <div>
                    <p class="text-sm font-bold text-ink">Catatan Hukum</p>
                    <ul class="mt-2 space-y-1 text-sm text-ink/80">
                        @foreach ($result['catatan'] as $c) <li>• {{ $c }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @if (! empty($result['dataKurang']))
                <div>
                    <p class="text-sm font-bold text-red-800">Data yang masih diperlukan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-700">
                        @foreach ($result['dataKurang'] as $d) <li>• {{ $d }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @if (! empty($result['aturan']))
                <div>
                    <p class="text-sm font-bold text-ink">Aturan yang digunakan</p>
                    <ul class="mt-2 space-y-1 text-xs text-ink/70">
                        @foreach ($result['aturan'] as $a)
                            <li>{{ $a['id'] }} — {{ $a['nama'] }} ({{ $a['status'] }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
                <strong>Catatan:</strong> Hasil ini adalah analisis, bukan keputusan pembagian final.
                Pembagian nominal belum tersedia karena aturan hukum perdata masih dalam tahap verifikasi.
            </div>
        </div>
    @endif
</div>
