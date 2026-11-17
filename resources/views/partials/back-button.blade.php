{{-- ===== Tombol kembali bersama (dipakai semua halaman fitur) =====
     Aturan: bila pengguna datang dari halaman lain dalam aplikasi ini,
     kembali ke sana; bila halaman dibuka langsung lewat URL (atau previous
     == halaman ini), arahkan ke beranda. Tidak pernah menghasilkan halaman
     kosong dan tidak menyentuh data apa pun. --}}
@php
    // Hanya percaya previous bila path-nya benar-benar halaman aplikasi
    // (whitelist route) — request teknis seperti /sw.js atau asset tidak dihitung.
    $pagePaths = collect([
        'home', 'heirs', 'shares', 'how-to', 'calculate',
        'history', 'sources', 'about', 'install', 'privacy',
    ])->map(fn ($name) => parse_url(route($name), PHP_URL_PATH));

    $prevPath = parse_url(url()->previous(), PHP_URL_PATH);
    $prevValid = $prevPath
        && $pagePaths->contains($prevPath)
        && $prevPath !== parse_url(url()->current(), PHP_URL_PATH);

    $backHref = $prevValid ? url()->previous() : ($fallback ?? route('home'));
@endphp
<a href="{{ $backHref }}"
   class="group inline-flex items-center gap-2 self-start rounded-full bg-emerald-100 py-2 pl-2.5 pr-3 text-emerald-900 ring-1 ring-emerald-200 transition hover:bg-emerald-200 active:scale-95 md:py-2 md:pl-2 md:pr-3.5"
   aria-label="Kembali ke halaman sebelumnya">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6-6-6 6 6 6"/>
    </svg>
    <span class="text-xs font-bold tracking-wide">{{ $label ?? 'Kembali' }}</span>
</a>
