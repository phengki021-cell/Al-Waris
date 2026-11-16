@extends('layouts.app')

@section('title', 'Admin — Kelola Ahli Waris')

@section('content')
<div class="flex items-center justify-between gap-3">
    <div>
        <p class="u-kicker text-gold-deep">Panel Admin</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Kelola Ahli Waris</h1>
        <p class="mt-1 text-sm text-ink/70">Perubahan di sini langsung tampil di halaman publik /ahli-waris.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">Dashboard</a>
        <a href="{{ route('admin.heirs.create') }}" class="u-btn-gold px-4 py-2 text-sm">+ Tambah</a>
    </div>
</div>

@if (session('status'))
    <p class="mt-4 rounded-xl bg-emerald-50 p-3 text-sm font-semibold text-emerald-800 ring-1 ring-emerald-200">{{ session('status') }}</p>
@endif

<div class="mt-5 overflow-x-auto">
    <table class="w-full min-w-[760px] text-left text-sm">
        <thead>
            <tr class="bg-emerald-900/5 text-xs uppercase tracking-wider text-emerald-900/80">
                <th class="px-3 py-2 font-semibold">Nama</th>
                <th class="px-3 py-2 font-semibold">Kode</th>
                <th class="px-3 py-2 font-semibold">Golongan</th>
                <th class="px-3 py-2 font-semibold">Maks</th>
                <th class="px-3 py-2 font-semibold">Status</th>
                <th class="px-3 py-2 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-emerald-900/10">
            @foreach ($heirs as $heir)
                <tr>
                    <td class="px-3 py-2 font-semibold text-ink/90">{{ $heir->name_id }}</td>
                    <td class="px-3 py-2 font-mono text-xs text-ink/70">{{ $heir->code }}</td>
                    <td class="px-3 py-2 text-ink/80">{{ $categories[$heir->category] ?? $heir->category }}</td>
                    <td class="px-3 py-2 text-ink/80">{{ $heir->max_share_numerator !== null ? $heir->max_share_numerator.'/'.$heir->max_share_denominator : '—' }}</td>
                    <td class="px-3 py-2">
                        <span class="rounded px-2 py-0.5 text-xs font-bold {{ $heir->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                            {{ $heir->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.heirs.edit', $heir) }}" class="rounded-lg bg-accent-soft px-3 py-1.5 text-xs font-bold text-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.heirs.destroy', $heir) }}" onsubmit="return confirm('Hapus {{ $heir->name_id }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-900 ring-1 ring-red-200 transition hover:bg-red-100">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
