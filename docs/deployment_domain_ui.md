# Panduan Deployment & Integrasi Domain Kampus UI (.ui.ac.id) — Al-Waris

> Dokumen ini memuat langkah-langkah teknis untuk mempublikasikan aplikasi **Al-Waris**
> secara permanen (aktif 24/7 dengan paket gratis) serta menghubungkan **domain kampus
> Universitas Indonesia (.ui.ac.id)** atau **domain gratis dari GitHub Student Developer
> Pack**.
>
> Status konfigurasi di repositori: **sudah siap-deploy** — meliputi `Dockerfile`,
> `docker-entrypoint.sh`, `.dockerignore`, `render.yaml`, dan `.env` berikut:
> `APP_NAME="Al-Waris"`, `APP_ENV=production`, `APP_DEBUG=false`,
> `APP_URL=https://alwaris.onrender.com`.
>
> Panduan umum Laravel/hosting lain: [docs/deployment.md](deployment.md) ·
> PWA & cache: [docs/pwa-and-cache.md](pwa-and-cache.md).

## 0. Ringkasan nama & URL yang sudah terpasang

| Item | Nilai | Lokasi berkas |
|---|---|---|
| Nama aplikasi | `Al-Waris` | `.env` (`APP_NAME`), `render.yaml` |
| Judul tab (title) | mengikuti `APP_NAME` | `resources/views/layouts/app.blade.php` |
| Manifest PWA (`name`/`short_name` | `Al-Waris — Edukasi & Kalkulator Faraid` / `Al-Waris` | `app/Http/Controllers/PwaController.php` |
| URL utama produksi | `https://alwaris.onrender.com` | `.env` (`APP_URL`), `render.yaml` |
| Nama service Render | `alwaris` → URL gratis `alwaris.onrender.com` | `render.yaml` (`name`) |

> **Mengapa `alwaris` tanpa tanda hubung?** URL gratis Render dibuat dari nama service:
> `alwaris` → `https://alwaris.onrender.com`. Jika nama `alwaris` sudah dipakai orang lain,
> Render memberi akhiran (mis. `alwaris-xxxx`); ubah kembali subdomain di
> **Dashboard → Service → Settings** atau sesuaikan `APP_URL`.

## 1. Persiapan Deployment Gratis (Render / Railway)

Aplikasi Laravel ini telah dilengkapi konfigurasi container/build script agar siap
dijalankan di cloud hosting gratis.

### 1.1 Cara yang disarankan — Render Blueprint Docker (tanpa perintah manual)

Repositori sudah berisi `render.yaml` (Blueprint). Render otomatis:
mem-build image Docker (`Dockerfile` → `composer install --no-dev`, `npm ci`, `npm run
build`), lalu `docker-entrypoint.sh` menjalankan migrasi + seeder + `php artisan
optimize` + `php artisan serve --host=0.0.0.0 --port=$PORT`.

1. **Push** repositori kode proyek ini ke GitHub.
2. Buat akun gratis di [Render.com](https://render.com).
3. Dashboard Render → **New → Blueprint** → hubungkan repositori GitHub proyek ini.
   Render membaca `render.yaml` → terima (Apply).
4. Variabel lingkungan sudah disematkan dari `render.yaml`
   (`APP_KEY` dibangkitkan otomatis, `APP_ENV=production`, `APP_DEBUG=false`,
   `APP_NAME=Al-Waris`, `APP_URL=https://alwaris.onrender.com`).
5. Tunggu build pertama (±10 menit), lalu buka `https://alwaris.onrender.com`.

Perhatikan dialog blueprint: bila Render meminta konfirmasi nilai `APP_URL`,
pastikan terisi `https://alwaris.onrender.com` (sesuaikan bila subdomain akhir berbeda).

### 1.2 Alternatif — runtime native PHP (Build/Start Command manual)

Bila Anda memilih **New → Web Service → Environment: PHP** (bukan Docker), gunakan
pengaturan berikut:

- **Build Command**:
  ```bash
  composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan key:generate --show && php artisan migrate --force
  ```
- **Start Command**:
  ```bash
  php artisan serve --host=0.0.0.0 --port=10000
  ```
- Runtime PHP milik Render menyediakan Composer & Node; port `10000` adalah port
  yang diekspos runtime native (untuk runtime **Docker**, abaikan dua perintah di atas —
  Dockerfile memakai `$PORT` otomatis).
- Isi variabel lingkungan dari file `.env` seperti tabel di awal dokumen
  (`APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `APP_NAME`).

> **Railway** juga bisa: New Project → Deploy from GitHub → Railway mendeteksi
> `Dockerfile` otomatis; variabel `.env` diisi lewat panel Variables.

> **Vercel**: pintas "Vercel" pada dokumen lama tidak didukung tanpa adaptor pihak
> ketiga (Vercel bukan runtime PHP/Laravel). Gunakan Render/Railway/Fly.io.

### 1.3 Verifikasi pasca-deploy

```text
https://alwaris.onrender.com/            → halaman beranda (200)
https://alwaris.onrender.com/up          → health check (200)
https://alwaris.onrender.com/manifest.json → application/json, name "Al-Waris"
https://alwaris.onrender.com/sw.js       → application/javascript
```

Catatan paket gratis Render: service **tidur** setelah ±15 menit tanpa lalu lintas;
kunjungan berikutnya bangun ulang ±30–60 detik. **URL tetap permanen** selama service
tidak dihapus.

## 2. Cara Klaim Domain Gratis Menggunakan Email UI (@ui.ac.id)

Sebagai Civitas Academica Universitas Indonesia, Anda dapat memanfaatkan program
**GitHub Student Developer Pack**:

1. Buka [GitHub Student Developer Pack](https://education.github.com/pack).
2. Klik **Sign up for Student Developer Pack**.
3. Daftarkan/hubungkan alamat email mahasiswa UI Anda (`@ui.ac.id`) serta siapkan
   foto KTM/KRS sebagai bukti status mahasiswa.
4. Setelah diverifikasi, Anda mendapatkan akses domain gratis ( dapat berubah, cek
   halaman Pack Anda):
   - **Namecheap**: domain `.me` gratis selama 1 tahun.
   - **Name.com**: domain `.live`, `.tech`, atau `.news` gratis selama 1 tahun.
5. Pilih nama yang memuat aplikasi, mis. `alwaris.me` / `alwaris.tech`.
6. Setelah klaim domain selesai, masuk ke dashboard Render → pilih service
   `alwaris` → **Settings → Custom Domains → Add Custom Domain** → masukkan
   `alwaris.me`.
7. Arahkan DNS sesuai instruksi Render yang muncul:
   - **Apex** (`alwaris.me`): A record → IP edge Render **atau** CNAME →
     `alwaris.onrender.com` (tulis di Namecheap → Advanced DNS).
   - **Subdomain** (`www.alwaris.me`): CNAME → `alwaris.onrender.com`.
   - Propagasi DNS ≈ 5–60 menit; **SSL/HTTPS** akan terpasang otomatis oleh Render.

## 3. Cara Menggunakan Subdomain Resmi Kampus UI (mis. `alwaris.ui.ac.id`)

Jika Anda ingin menggunakan subdomain resmi di bawah domain utama `ui.ac.id`:

### 3.1 Pengajuan ke DSTI / IT UI

- Kirimkan permohonan ke unit Pengelola IT/**DSTI UI** (atau lewat BEM/Himpunan
  Fakultas) untuk alokasi subdomain, contoh: `alwaris.ui.ac.id`.
  (Nama `warisku.ui.ac.id` dari dokumen lama dapat dipakai juga; konsistenkan
  dengan `APP_URL`.)
- Sertakan: identitas mahasiswa/NPM, deskripsi singkat situs (kalkulator faraid
  edukasi), dan alasan permohonan hosting.

### 3.2 Setup CNAME Record

- Berikan nilai **CNAME Target** dari hosting Anda kepada administrator IT UI
  (contoh: `alwaris.onrender.com`).
- Minta administrator IT UI mengarahkan CNAME:
  `alwaris.ui.ac.id  CNAME  alwaris.onrender.com`
- (Cara memverifikasi target: buka dashboard Render → service → Settings →
  Custom Domains → nilai yang tampil adalah `alwaris.onrender.com`.)

### 3.3 Konfigurasi di Hosting

1. Di dashboard Render → service `alwaris` → **Settings → Custom Domains** →
   tambahkan `alwaris.ui.ac.id`.
2. Tunggu DNS terverifikasi; **SSL/HTTPS** akan terpasang otomatis (Let's Encrypt
   melalui Render) — tidak ada yang perlu diatur manual.
3. Perbarui `APP_URL` di dashboard Render (**Environment**) menjadi
   `https://alwaris.ui.ac.id` bila itu menjadi URL utama, agar tautan absolut
   (OG/e-mail) konsisten. Di `.env` repositori, samakan nilainya.

### 3.4 Checklist akhir domain

- [ ] `alwaris.ui.ac.id` (atau domain student pack) sudah merespons HTTPS 200.
- [ ] `/manifest.json` & `/sw.js` merespons 200 dengan tipe konten benar.
- [ ] Tombol **Instal** PWA muncul di Chrome Android; instalasi berfungsi.
- [ ] `APP_URL` di Render Environment & `.env` sama dengan domain final.
- [ ] APP_DEBUG tetap `false`; log tidak menampilkan error di dashboard Render.

## 4. Ringkasan perintah build yang dipakai di mana

| Konteks | Perintah | Catatan |
|---|---|---|
| Render **Docker** (direkomendasikan) | — (dibaca dari `Dockerfile` + `render.yaml`) | Tidak perlu Build/Start Command |
| Render **PHP native** | `composer install --no-dev --optimize-autoloader && npm ci && npm run build` + start `php artisan serve --host=0.0.0.0 --port=10000` | §1.2 |
| Lokal (Windows) | `php -d memory_limit=1G tools\composer.phar install` · `npm.cmd install` · `npm.cmd run build` | lihat README |
| CI/test mandiri | `php artisan test` | pas hijau (lihat laporan QA terakhir: 79 test / 393 assertion) |
