# PWA — Rencana Tahap 4

## Status Tahap 1
- ✅ `public/manifest.json` placeholder (nama, warna tema `#123D32`, latar `#F5F7F4`).
- ✅ Tag `<link rel="manifest">` di layout.
- ✅ Meta `theme-color`.
- ⏳ Ikon `public/icons/icon-192.png` & `icon-512.png` — dibuat pada Tahap 4 (butuh desain logo).
- ⏳ `public/sw.js` — service worker strategi cache (lihat `docs/architecture.md` §4) di Tahap 4.
- ⏳ Registrasi SW di frontend + halaman "instal ke beranda" pada Tentang.

## Catatan penting (wajib)
- PWA **bukan APK native**. Pernyataan ini ada di `/tentang`, README, dan materials.
- **HTTPS wajib saat deployment**; SW hanya stabil di secure context.
- SW tidak boleh menyimpan data sensitif / hasil perhitungan pribadi di cache; jika ada
  penyimpanan offline perhitungan, gunakan IndexedDB dengan persetujuan eksplisit pengguna.
