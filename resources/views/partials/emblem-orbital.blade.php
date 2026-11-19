{{-- =========================================================
     Emblem orbital astrolab Islam futuristik (DARI belakang, dekoratif).
     Berlapis: 4 lingkaran/ornamen dengan arah & kecepatan berbeda,
     titik emas pada lintasan orbit, glow emas berdenyut lembut.
     Logo Al-Waris di PUSAT tetap diam. Wadah pointer-events: none.
     Dipakai via: @include('partials.emblem-orbital')
     ========================================================= --}}
<div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
    <div class="relative aspect-square w-[min(100%,34rem)] opacity-70 md:opacity-80">

        {{-- Glow emerald-toska + emas yang berdenyut sangat lembut --}}
        <div class="u-orb-glow absolute inset-[6%] rounded-full"
             style="background:
                 radial-gradient(closest-side, rgba(20,127,112,0.4), transparent 70%),
                 radial-gradient(closest-side at 50% 50%, rgba(216,182,106,0.26), transparent 85%);
                 --orb-dur: 7s;"></div>
        <div class="u-orb-glow absolute inset-[24%] rounded-full"
             style="background: radial-gradient(closest-side, rgba(216,182,106,0.3), transparent 75%);
                 --orb-dur: 9s; animation-delay: -3s;"></div>

        {{-- LAPIS 1 â€” lingkaran terluar: garis putus-putus + 12 tick, berputar SEARAH jarum jam, paling lambat --}}
        <svg class="u-orb-cw absolute inset-0" viewBox="0 0 100 100" style="--orb-dur: 110s;">
            <circle cx="50" cy="50" r="48" fill="none" stroke="rgba(216,182,106,0.75)" stroke-width="0.35" stroke-dasharray="0.6 2.2"/>
            <circle cx="50" cy="50" r="46.5" fill="none" stroke="rgba(216,182,106,0.45)" stroke-width="0.25"/>
            <g stroke="rgba(216,182,106,0.6)" stroke-width="0.5" stroke-linecap="round">
                <line x1="50" y1="2.2" x2="50" y2="4.6"/>
                <line x1="50" y1="95.4" x2="50" y2="97.8"/>
                <line x1="2.2" y1="50" x2="4.6" y2="50"/>
                <line x1="95.4" y1="50" x2="97.8" y2="50"/>
                <line x1="16.4" y1="16.4" x2="18" y2="18"/>
                <line x1="82" y1="82" x2="83.6" y2="83.6"/>
                <line x1="83.6" y1="16.4" x2="82" y2="18"/>
                <line x1="18" y1="82" x2="16.4" y2="83.6"/>
            </g>
            {{-- Titik emas mengikuti lintasan orbit terluar --}}
            <circle cx="50" cy="2.9" r="1" fill="#D8B66A"/>
            <circle cx="97.1" cy="50" r="0.8" fill="#2FBF8F" opacity="0.9"/>
            <circle cx="50" cy="97.1" r="0.7" fill="#D8B66A" opacity="0.8"/>
        </svg>

        {{-- LAPIS 2 â€” bintang 8 (dua persegi) + lingkaran kecil, berputar BERLAWANAN arah --}}
        <svg class="u-orb-ccw absolute inset-[6.5%]" viewBox="0 0 100 100" style="--orb-dur: 80s;">
            <g fill="none" stroke="rgba(216,182,106,0.65)" stroke-width="0.4">
                <rect x="14" y="14" width="72" height="72" rx="3"/>
                <rect x="14" y="14" width="72" height="72" rx="3" transform="rotate(45 50 50)"/>
            </g>
            <g fill="none" stroke="rgba(47,191,143,0.55)" stroke-width="0.3">
                <circle cx="50" cy="14" r="2.2"/>
                <circle cx="86" cy="50" r="2.2"/>
                <circle cx="50" cy="86" r="2.2"/>
                <circle cx="14" cy="50" r="2.2"/>
            </g>
            {{-- Titik emas pada titik-titik bintang --}}
            <circle cx="50" cy="11.8" r="0.9" fill="#D8B66A"/>
            <circle cx="88.2" cy="50" r="0.9" fill="#D8B66A" opacity="0.85"/>
            <circle cx="50" cy="88.2" r="0.9" fill="#2FBF8F" opacity="0.8"/>
        </svg>

        {{-- LAPIS 3 â€” lingkaran tengah: pola geometri halus + titik emas, SEARAH, lebih cepat --}}
        <svg class="u-orb-cw absolute inset-[15%]" viewBox="0 0 100 100" style="--orb-dur: 60s;">
            <circle cx="50" cy="50" r="47" fill="none" stroke="rgba(216,182,106,0.55)" stroke-width="0.3" stroke-dasharray="2.5 1.6"/>
            <g fill="none" stroke="rgba(216,182,106,0.6)" stroke-width="0.35">
                <path d="M50 8 L86 50 L50 92 L14 50 Z"/>
                <path d="M50 8 L50 92 M14 50 L86 50" opacity="0.5"/>
            </g>
            <circle cx="50" cy="3" r="1" fill="#D8B66A"/>
            <circle cx="97" cy="50" r="0.8" fill="#D8B66A" opacity="0.75"/>
        </svg>

        {{-- LAPIS 4 â€” poligon dalam berlawanan arah, paling samar --}}
        <svg class="u-orb-ccw absolute inset-[26%]" viewBox="0 0 100 100" style="--orb-dur: 120s;">
            <g fill="none" stroke="rgba(47,191,143,0.45)" stroke-width="0.3">
                <circle cx="50" cy="50" r="46"/>
                <path d="M50 6 L90.5 28 L90.5 72 L50 94 L9.5 72 L9.5 28 Z"/>
            </g>
            <circle cx="50" cy="4" r="0.8" fill="#2FBF8F" opacity="0.85"/>
        </svg>

    </div>
</div>
