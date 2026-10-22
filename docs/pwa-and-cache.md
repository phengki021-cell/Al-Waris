# PWA & Strategi Cache — Al-Waris

> Status: Tahap 4. Al-Waris adalah **PWA (Progressive Web App)** — website yang bisa
> dipasang dari browser (Android Chrome, Edge desktop, iOS Safari). **Bukan aplikasi
> native, tidak ada file APK** yang dibangun/tersedia.

## 1. File PWA yang dibuat

| File | Peran |
|---|---|
| `app/Http/Controllers/PwaController.php` | Menyediakan `/manifest.json` (JSON) & `/sw.js` via route supaya teruji otomatis tanpa web server statis |
| `resources/pwa/sw.js` | Sumber service worker (dibaca controller; diserving dengan `Cache-Control: no-cache` dan `Service-Worker-Allowed: /`) |
| `resources/js/app.js` | Registrasi service worker (aman: hanya HTTPS atau localhost) + pembaruan cache (`SKIP_WAITING`, reload sekali pada `controllerchange`) |
| `resources/views/pages/offline.blade.php` | Halaman fallback offline (`/offline`) |
| `resources/views/pages/install.blade.php` | Panduan instalasi Android Chrome/Edge/iOS (`/instalasi`) |
| `public/icons/` | `icon-192.png`, `icon-512.png`, `maskable-192.png`, `maskable-512.png`, `apple-touch-icon-180.png`, `favicon-48.png` — seluruhnya diturunkan dari **logo resmi** `resources/brand/logo-al-waris.jpg` oleh `scripts/generate-icons.php` (hasil di-commit); `public/favicon.ico` juga ikut digenerate (PNG-in-ICO 48px) |
| manifest | name "Al-Waris — Edukasi & Kalkulator Faraid", short_name "Al-Waris", start_url `/`, scope `/`, display `standalone`, theme `#123D32`, background `#F5F7F4`, ikon `any` + `maskable` 192/512 |

## 2. Strategi cache (service worker)

Kunci cache: `al-waris-v2` (`CACHE_VERSION`). Aturan fetch:

1. **Non-GET → diteruskan tanpa cache.**
2. **Lintas origin (CDN font) → passthrough, tidak di-cache** di service worker.
3. **Halaman berdata pengguna (kalkulator & riwayat) → network-only**:
   `/hitung-warisan`, `/riwayat`, `/livewire/update`, dan URL apa pun yang
   memuat `livewire`. Bila offline: navigasi → halaman offline; request AJAX
   → jawaban 503. **Hasil kalkulasi tidak pernah tertulis ke cache, URL, atau log.**
4. **Aset statis (cache-first)**: `/build/*` (Vite, ber-hash), `/icons/*`, `/fonts/*`
   dan ekstensi statis `css/js/woff2/png/svg/jpg/webp/ico`. Setelah not-cache-miss,
   jawaban 200 disimpan ke cache.
5. **Navigasi HTML lainnya → network-first**; gagal → fallback halaman `/offline`
   (HTML edukasi tidak di-cache, jadi konten selalu segar saat online).
## 3. Pembaruan versi cache

- Naikkan `CACHE_VERSION` (`al-waris-v2`, dst.) setiap aset berubah.
- Saat aktivasi, semua cache selain versi aktif dihapus; `clients.claim()` mengambil
  alih halaman terbuka.
- Halaman mengirim pesan `SKIP_WAITING` ke worker baru yang menunggu sehingga
  pembaruan langsung aktif, lalu memuat ulang sekali (perulangan reload dijaga
  di `resources/js/app.js`). `/sw.js` diserving `no-cache` sehingga
  browser segera melihat worker terbaru.

## 4. Perilaku offline

- Halaman **/offline, ikon, favicon, aset build yang pernah diambil** tetap tersedia.
- Halaman kalkulator & riwayat **sengaja tidak tersedia offline**; bila koneksi
  putus saat membuka, pengguna melihat halaman offline yang menyatakan hal ini.
  Riwayat tersimpan di server berbasis sesi — tanpa koneksi memang tidak bisa ditampilkan.
- PWA yang dipasang tetap membuka jendela-nya sendiri, tapi butuh jaringan untuk
  menghitung & melihat riwayat.

## 5. Penyimpanan riwayat (batasan)

- Riwayat = baris di SQLite server yang dikaitkan token sesi browser (anonim);
  hanya angka harta, daftar ahli waris, hasil bagi, kode referensi `ALW-*`, metode,
  dan **versi mesin hitung** (`engine_version`).
- Tanpa akun/login; sinkronisasi lintas-perangkat **tidak ada**; bila kelak
  ditambahkan, perlu persetujuan eksplisit pengguna sebelum sinkron pertama.
- Hapus: per-baris di halaman Riwayat (dengan konfirmasi). Mereset cookie sesi
  juga memutus akses.
- Tidak ada data sensitif yang di-cache SW, diletakkan di URL, ditulis ke log,
  atau disimpan di storage publik.

## 6. Batasan browser & hal yang perlu diketahui

- Instalasi PWA butuh **HTTPS** pada deployment; `http://localhost` (dan 127.0.0.1)
  dikecualikan untuk pengujian pengembangan.
- **Label tombol instal berbeda antar-browser** (Chrome: "Instal aplikasi", beberapa
  browser: "Tambahkan ke layar utama"). Jika manifest/ikon belum dianggap memenuhi
  syarat, tombol instal bisa tidak tampil.
- iOS: "Tambah ke Layar Utama" dari menu Bagikan; dukungan offline/instal
  bervariasi antar-versi iOS.
- Pencabutan worker baru: menunggu hingga reload dilakukan lewat `SKIP_WAITING`.
- Installing to home screen di iOS **tidak** memberikan prompt otomatis — manual via Safari.

## 7. Pengujian

Dilakukan di `tests/Feature/PwaTest.php`:
- manifest: field name/display/start_url/icons (termasuk ikon `maskable`),
- sw.js: memuat versi `al-waris-v1`, memuat `/offline`; **tidak** meng-cache
  `/hitung-warisan`, `/riwayat`, `/livewire/update` (network-only),
- `/offline` dan `/instalasi` merespons 200,
- 404 halaman informatif (PagesTest dataset),
- ikon ada di disk dengan dimensi yang benar,
- banner instalasi di beranda & logika `app.js` (section 8).

## 8. Banner ajakan instal (modal popup beranda)

Saat pertama kali membuka halaman utama `/`, `resources/js/app.js` menampilkan
banner **"Instal Aplikasi Waris Syariah untuk Akses Cepat"** dengan tombol
**"Instal Sekarang"** dan **"Nanti Saja"** (markup di `pages/home.blade.php`,
`#pwa-install-banner`, awalnya `hidden`).

- **Instal Sekarang**: memakai event `beforeinstallprompt` (Chrome/Edge/Android).
  Bila tidak tersedia (mis. iOS Safari), preferensi disimpan `later` dan
  pengguna diarahkan ke `/instalasi` untuk panduan manual.
- **Nanti Saja / tutup**: preferensi `later` disimpan di
  `localStorage['al-waris-install-prompt']` — banner diulang 30 hari kemudian.
  Pilihan `installed` (prompt diterima) menghentikan pengingat permanen.
- Banner **tidak** tampil bila aplikasi sudah terbuka sebagai standalone
  (`display-mode: standalone` / iOS `navigator.standalone`), dan hanya
  pada halaman utama.
