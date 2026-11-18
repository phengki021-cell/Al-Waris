{{-- Logo Al-Waris: timbangan keadilan emas + kitab + bintang 8 ornamen.
     Dipakai via: @include('partials.logo', ['class' => 'h-6 w-6'])
     Warna tetap (gold/emerald) agar tampil di kedua tema terang & gelap. --}}
@php $class = $class ?? 'h-6 w-6'; @endphp
<svg class="{{ $class }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo Al-Waris">
    {{-- Bintang 8 ornamen Islami (halus, di belakang) --}}
    <g stroke="rgba(216,182,106,0.32)" stroke-width="1.3" aria-hidden="true">
        <rect x="9.5" y="9.5" width="29" height="29" rx="2"/>
        <rect x="9.5" y="9.5" width="29" height="29" rx="2" transform="rotate(45 24 24)"/>
    </g>

    {{-- Tiang & lengan timbangan keadilan --}}
    <path d="M24 12.5v21" stroke="#D8B66A" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M12.5 17h23" stroke="#D8B66A" stroke-width="2.2" stroke-linecap="round"/>
    <circle cx="24" cy="11.4" r="1.6" fill="#D8B66A"/>

    {{-- Tali & mangkuk timbangan kiri --}}
    <path d="M12.5 17v1.4" stroke="#D8B66A" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M12.5 18.4l-3.2 6.4a3.6 3.6 0 0 0 6.4 0Z" fill="rgba(216,182,106,0.28)" stroke="#D8B66A" stroke-width="1.7" stroke-linejoin="round"/>

    {{-- Tali & mangkuk timbangan kanan --}}
    <path d="M35.5 17v1.4" stroke="#D8B66A" stroke-width="1.6" stroke-linecap="round"/>
    <path d="M35.5 18.4l-3.2 6.4a3.6 3.6 0 0 0 6.4 0Z" fill="rgba(216,182,106,0.28)" stroke="#D8B66A" stroke-width="1.7" stroke-linejoin="round"/>

    {{-- Kitab faraid di dasar --}}
    <path d="M14.5 34.5c3-1.7 6.3-1.7 9.5 0 3.2-1.7 6.5-1.7 9.5 0v4.2c-3-1.7-6.3-1.7-9.5 0-3.2-1.7-6.5-1.7-9.5 0Z"
          stroke="#2FBF8F" stroke-width="1.8" stroke-linejoin="round"/>
    <path d="M24 34.5v4.2" stroke="#2FBF8F" stroke-width="1.8"/>
</svg>
