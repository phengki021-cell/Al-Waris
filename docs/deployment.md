# Deployment — Al-Waris

> Panduan deployment untuk hosting PHP/Laravel. **Dukungan di shared hosting sangat
> bergantung pada akses server & konfigurasi host (versi PHP, SSH, Composer, queue,
> batas eksekusi); jangan asumsikan kompatibel sebelum diperiksa.** Shared hosting
> tanpa SSH umumnya **tidak cukup nyaman** untuk aplikasi Laravel modern — VPS /
> cloud VM / PaaS PHP disarankan.

## 1. Persyaratan server produksi

| Item | Nilai |
|---|---|
| PHP | ≥ 8.3 (dicocokkan dengan kebutuhan `composer.json`) |
| Ekstensi PHP | `pdo_mysql`/`pdo_pgsql`/`pdo_sqlite`, `mbstring`, `openssl`, `dom`, `xml`, `curl`, `fileinfo`, `intl`, `gd` (untuk regenerasi ikon, opsional) |
| Composer | tersedia via SSH (atau jalankan `composer install` lokal lalu upload `vendor/`) |
| Node.js + npm | untuk build aset (bisa juga di CI/lalu commit `public/build/`) |
| Database | MySQL/MariaDB/PostgreSQL (SQLite hanya untuk dev/kecil) |
| HTTPS | **wajib** untuk PWA instalasi; sertifikat Let's Encrypt/cPanel AutoSSL |

## 2. Konfigurasi environment (`.env` produksi)

```ini
APP_NAME="Al-Waris"
APP_ENV=production
# PENTING: APP_DEBUG HARUS false di produksi (mencegah kebocoran stacktrace/config)
APP_DEBUG=false
APP_URL=https://al-waris.example

# APP_KEY baru khusus produksi — jangan pakai key development,
# jangan pernah disimpan ke repository.
APP_KEY=

# Contoh MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3406  # sesuaikan; default MySQL 3306
DB_DATABASE=alwaris
DB_USERNAME=...
DB_PASSWORD=...

FILESYSTEM_DISK=local
SESSION_DRIVER=database
CACHE_STORE=database
LOG_LEVEL=warning
```

- `.env.example` memang berisi semua kosong (`APP_KEY=`) — bukan secret.
- Aplikasi ini **tidak menyimpan API key/third-party secret**; tidak ada service eksternal berbayar.
- Jangan pernah commit `.env`.

## 3. Langkah deploy (urutan standar Laravel)

```bash
# Di mesin build (lokal/CI):
composer install --no-dev --optimize-autoloader
npm ci && npm run build             # hasil: public/build/* ber-hash

# Upload kode (git pull atau rsync, TANPA vendor & node_modules bila host punya composer)

# Di server:
composer install --no-dev --optimize-autoloader   # bila composer SSH tersedia
php artisan key:generate --force                  # sekali, pertama
php artisan migrate --force                       # membuat semua tabel
php artisan storage:link                          # hanya bila akan pakai disk publik (opsional)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Hanya folder `storage/` dan `bootstrap/cache/` yang perlu **writable** oleh user PHP
  (permission 775, owner sesuai user web) — sesuai panduan resmi Laravel.
- **Storage link tidak wajib** untuk fitur saat ini (tidak ada unggahan publik) — jalankan
  hanya jika rencananya ada file disk publik.
- `php artisan serve` **bukan web server produksi**; gunakan Nginx/Apache dengan document
  root di **folder `public/`**.
- Cache aplikasi (`config:cache` dll.) lakukan **di server**, bukan di mesin build.

### Contoh blok Nginx

```nginx
server {
    listen 443 ssl;
    server_name al-waris.example;
    root /var/www/al-waris/public;      # PENTING: arahkan ke public/
    index index.php;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
}
server { listen 80; server_name al-waris.example; return 301 https://$host$request_uri; }
```

## 4. Database: backup & restore

```bash
# MySQL
mysqldump -u USER -p DBNAME > backup-$(date +%F).sql
# Restore
mysql -u USER -p DBNAME < backup-2026-10-21.sql
```

- SQLite (dev/kecil): salin `database/database.sqlite` saat dev server tidak sedang
  menulis (atau pakai `sqlite3 .backup`).
- Riwayat kalkulasi hanya terdiri dari data anonim + token sesi — backup cukup harian.
- Kembalikan dengan urutan: restore dump → `php artisan migrate --force` (untuk migrasi
  yang lebih baru dari backup).

## 5. Verifikasi pasca-deploy

- [ ] `https://…/manifest.json` merespons 200 `application/json`
- [ ] `https://…/sw.js` merespons 200 `application/javascript` (bukan HTML error page)
- [ ] Buka situs di Android Chrome → tombol **Instal** muncul di menu browser
- [ ] Setelah dipasang: ikon muncul di layar utama dan membuka jendela/layar penuh tersendiri (mode standalone)
- [ ] Mode pesawat → membuka navigasi → melihat halaman offline yang informatif
- [ ] Menghitung 1 kasus dan menyimpan / menghapus riwayat normal
- [ ] `APP_DEBUG=false` — halaman error tak menampilkan stacktrace

## 6. Pembaruan cache PWA

Saat mengubah aset/judul SW: naikkan `CACHE_VERSION` pada `resources/pwa/sw.js`
(`al-waris-v1` → `al-waris-v2`, dst). Lihat [docs/pwa-and-cache.md](docs/pwa-and-cache.md).

## 7. Batasan shared hosting

Bila hanya tersedia shared hosting tanpa SSH:
1. Build aset di lokal, commit `public/build/`, upload **seluruh** proyek termasuk `vendor/`.
2. Pastikan versi PHP melalui cPanel "PHP Options" bisa dipilih ≥ 8.3.
3. ModSecurity pada sebagian shared hosting kadang memblokir `POST /livewire/update`
   sehingga kalkulator gagal — bila terjadi, minta host mengizinkan endpoint tersebut
   atau cek error log host.
4. HTTPS dapat diaktifkan dari cPanel — **wajib** aktif sebelum menguji instalasi PWA.

## 8. Demo publik sementara (tunnel dari mesin pengembangan)

Untuk demo cepat ke orang lain **tanpa hosting**, gunakan Cloudflare Quick Tunnel
(`tools/cloudflared.exe`, sudah tersedia):

```powershell
# Terminal 1 — server Laravel
php artisan serve --port=8018

# Terminal 2 — tunnel publik HTTPS + domain acak gratis, tanpa akun
tools\cloudflared.exe tunnel --url http://127.0.0.1:8018 --no-autoupdate
```

Output mencetak sebuah URL `https://<kata-acak>.trycloudflare.com` yang bisa dibagikan —
HTTPS otomatis, jadi PWA (manifest, service worker, instalasi Android) langsung berfungsi
dari domain tersebut.

**Batasan mode demo ini — wajib diketahui:**
1. Situs hanya hidup selama **mesin pengembangan dan kedua perintah berjalan**.
2. Subdomain **acak berubah setiap kali** tunnel dijalankan ulang; pintasan instal
   lama tidak berlaku (ikon terpasang lama tak tertaut ke domain baru).
3. Ini **bukan** hosting produksi: tanpa SLA/pengawas, dan APP_KEY milik mesin dev.
   (APP_DEBUG sudah `false` di `.env` untuk mode publik ini.)

## 9. Ke permanen (dapat diakses semua orang 24/7)

1. Sewa VPS/cloud murah (Ubuntu + PHP 8.3-fpm + MySQL) atau PaaS PHP.
2. Arahkan domain → DNS A/CNAME → pakai `cloudflared tunnel` ber-token dari **domain
   milik Anda** (subdomain stabil, bukan acak) **atau** Nginx + Let's Encrypt seperti §3.
3. Deploy ikuti §3 (migrate, cache config/route/view), verifikasi ikut §5.

## 10. Deploy permanen GRATIS ke Render.com (Blueprint, 24/7, URL tetap)

Repository sudah siap-deploy: `Dockerfile` (build aset + runtime PHP),
`docker-entrypoint.sh` (APP_KEY otomatis, migrate + seed bijak boot, `php artisan
serve --port=$PORT`), `.dockerignore`, dan `render.yaml` (Blueprint). Semua cukup
hosting cakupan gratis satu service; **URL tidak berubah** meski laptop mati.
Panduan domain gratis Student Pack & subdomain kampus `.ui.ac.id`:
[deployment_domain_ui.md](deployment_domain_ui.md).

### 10.1 Langkah (±10 menit)

1. Push seluruh proyek ke repo **publik/private** GitHub Anda.
2. `php artisan key:generate --show` di mesin dev → salin nilai (pilihannya: biarkan
   `render.yaml` membuat APP_KEY otomatis lewat `generateValue: true` — cukup sekali
   deploy, APP_KEY stabil selama env tersimpan).
3. Buka `dashboard.render.com` → **New** → **Blueprint** → pilih repo; Render membaca
   `render.yaml` → tulis nama service mis. `alwaris` → **Apply**.
4. Saat dialog env: isi `APP_URL` dengan URL service (`https://alwaris.onrender.com`).
5. Build pertama jalan: Dockerfile compile aset, composer install --no-dev, lalu
   entrypoint menjalankan migrate + seeder + `php artisan optimize` + serve.
6. Verifikasi: buka URL → halaman utama 200, `/up` 200 (health check), coba hitung
   warisan, dan tombol Instal PWA di Android Chrome.

### 10.2 Yang perlu diketahui paket gratis

| Aspek | Perilaku |
|---|---|
| Sleep otomatis | Idle ±15 mnt → service tidur; kunjungan berikutnya bangun ulang ±30–60 dtk (URL tetap). |
| Disk | Ephemeral; setiap redeploy/restart DB SQLite dibuat ulang lalu seeder mengisi data demo. Riwayat perhitungan pengguna ikut kosong (bukan data sensitif). |
| 750 jam/bulan | Cukup untuk **satu** service 24/7. |
| Domain sendiri | Gratis — Settings → Custom Domains (DNS diarahkan ke Render). PWA tetap berfungsi penuh. |
| Railway/Fly.io alternatif | Sama-sama container-based; Railway punya pengalihat kredit awal terbatas, Fly.io kini mungkin butuh kartu. Alur: build image sama, jalankan entrypoint sama. |

### 10.3 Pemeliharaan

- Deploy ulang (push git) → otomatis (autoDeploy: true) atau manual dari dashboard.
- Reset data demo: `SKIP_SEED=0` (bawaan) agar seed master dijalankan tiap boot; set `SKIP_SEED=1` untuk mempercepat boot tanpa seed.
- Log & health: dashboard Render → service → Logs/Events; health `/up`.

