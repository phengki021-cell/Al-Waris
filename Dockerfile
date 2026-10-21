# Al-Waris — image produksi siap-deploy ke layanan container (Render, VPS, dst.)
# Build lokal:  docker build -t al-waris .
# Jalankan:     docker run -e APP_URL=https://contoh.com -p 8080:8080 al-waris
#
# Tahap 1: build aset Vite/Tailwind (public/build)
# Tahap 2: runtime PHP 8.3 (php artisan serve, menghormati $PORT) + composer --no-dev
#          + entrypoint (APP_KEY otomatis bila kosong, migrate & seed, cache config)

# ── Tahap 1: build aset ───────────────────────────────────────────────────────
FROM node:22-alpine AS assets

WORKDIR /src

COPY package.json package-lock.json ./
COPY vite.config.js ./
COPY resources/css ./resources/css
COPY resources/js ./resources/js
COPY resources/views ./resources/views
COPY public/icons ./public/icons
COPY public/favicon.ico* ./public/
COPY public/fonts* ./public/

RUN npm ci --no-audit --no-fund && npm run build

# ── Tahap 2: runtime PHP ─────────────────────────────────────────────────────
FROM php:8.3-cli-alpine

WORKDIR /var/www/html

# Ekstensi Laravel yang diperlukan (mbstring & gd dibundel; intl & zip wajib)
RUN apk add --no-cache icu-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        opcache \
        pcntl \
        pdo \
        pdo_sqlite \
        zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dependensi PHP produksi (layer terpisah agar rebuild cepat)
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --prefer-dist \
        --optimize-autoloader

# Sumber aplikasi + aset hasil Tahap 1 (public/build sudah berisi ikon dsb.)
COPY . .
COPY --from=assets /src/public/build ./public/build

RUN php artisan package:discover --ansi \
    && php -r 'file_exists("database/database.sqlite") || touch("database/database.sqlite");'

# Lokasi kerja baca-tulis: storage (log/cache/view), bootstrap, database SQLite
RUN chmod -R ug+rwX storage bootstrap/cache database \
    && find storage bootstrap/cache database -type d -exec chmod 775 {} +

EXPOSE 8080

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

# Render memakai healthCheckPath: /up; ini hanya untuk Docker lokal
HEALTHCHECK --interval=30s --timeout=5s --start-period=45s --retries=3 \
    CMD wget -q --spider "http://127.0.0.1:${PORT:-8080}/up" || exit 1
