<div class="mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold md:text-3xl">Riwayat Perhitungan</h1>
    <p class="mt-2 text-sm text-ink/70">
        Riwayat tersimpan <strong>anonim per sesi peramban</strong>. Data tetap di perangkat/peramban Anda
        dan tidak terhubung ke akun mana pun. Lihat <a href="{{ route('privacy') }}" class="text-primary underline">Kebijakan Privasi</a>.
    </p>

    @if (session()->has('message'))
        <p class="mt-4 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-800 ring-1 ring-green-200">
            {{ session('message') }}
        </p>
    @endif

    @if ($calculations->isEmpty())
        <div class="mt-8 rounded-xl bg-paper p-10 text-center shadow-sm ring-1 ring-black/5">
            <p class="text-4xl" aria-hidden="true">🗂️</p>
            <p class="mt-3 font-medium">Belum ada riwayat perhitungan.</p>
            <p class="mt-1 text-sm text-ink/60">
                Hitung warisan terlebih dahulu di
                <a href="{{ route('calculate') }}" class="text-primary underline">Halaman Hitung Warisan</a>,
                lalu klik <strong>Simpan ke Riwayat</strong>.
            </p>
        </div>
    @else
        <ul class="mt-6 space-y-3">
            @foreach ($calculations as $calculation)
                <li class="rounded-xl bg-paper p-4 shadow-sm ring-1 ring-black/5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="text-sm">
                            <p class="font-semibold text-primary">{{ $calculation->reference_code }}</p>
                            <p class="mt-1 text-xs text-ink/60">
                                {{ $calculation->created_at->format('d M Y H:i') }}
                                · Metode: {{ ($calculation->method ?? '—') }}
                                · Status: {{ strtoupper($calculation->status) }}
                            </p>
                            <p class="mt-2">
                                Harta awal: <strong>Rp {{ number_format((float) $calculation->estate_total, 2, ',', '.') }}</strong>
                                · Hutang: Rp {{ number_format((float) $calculation->debts_total, 2, ',', '.') }}
                                · Wasiat: Rp {{ number_format((float) $calculation->bequests_total, 2, ',', '.') }}
                            </p>
                            <p class="mt-1">
                                Harta bersih dibagi: <strong>Rp {{ number_format((float) $calculation->net_estate, 2, ',', '.') }}</strong>
                            </p>
                            @if ($calculation->participants->isNotEmpty())
                                <ul class="mt-2 space-y-0.5 text-xs text-ink/70">
                                    @foreach ($calculation->participants as $participant)
                                        <li>
                                            {{ $participant->name_id }}
                                            @if ((int) $participant->count > 1)
                                                ×{{ $participant->count }}
                                            @endif
                                            —
                                            @foreach ($participant->results as $result)
                                                {{ $result->share_text }}
                                                @if ($result->amount != 0)
                                                    (Rp {{ number_format((float) $result->amount, 2, ',', '.') }})
                                                @endif
                                    @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        @if (array_key_exists($calculation->id, $this->confirmingDelete))
                            <div class="text-right">
                                <p class="text-xs text-ink/70">Hapus riwayat ini?</p>
                                <div class="mt-2 flex gap-2">
                                    <button wire:click="delete({{ $calculation->id }})"
                                        class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">
                                        Ya, hapus
                                    </button>
                                    <button wire:click="cancelDelete({{ $calculation->id }})"
                                        class="rounded-lg border border-ink/20 px-3 py-1.5 text-xs font-medium">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        @else
                            <button wire:click="confirmDelete({{ $calculation->id }})"
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                Hapus
                            </button>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $calculations->links() }}
        </div>
    @endif
</div>
