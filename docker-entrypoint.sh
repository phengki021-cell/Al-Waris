#!/bin/sh
#
# Entrypoint container Al-Waris (Render/VPS/lokal):
#  1. APP_KEY: pakai env; bila kosong, generate acak (ephemeral — agar tetap
#     permanen, set APP_KEY di layanan hosting hasil `php artisan key:generate --show`).
#  2. Migrasi database + seeder DEMO (idempotent).
#  3. Cache config/route/view (php artisan optimize).
#  4. Jalankan php artisan serve pada $PORT (default 8080; Render otomatis set).
#
set -e
cd /var/www/html

if [ -z "${APP_KEY}" ]; then
    GENERATED_KEY=$(php -r 'echo "base64:".base64_encode(random_bytes(32));')
    export APP_KEY="$GENERATED_KEY"
    echo "APP_KEY baru dibangkitkan kali ini saja (ephemeral). Set APP_KEY di server agar tetap."
fi

echo "Menjalankan migrasi database..."
php artisan migrate --force

if [ "${SKIP_SEED:-0}" != "1" ]; then
    echo "Menyinkron data master DEMO (heirs/shares/sources)..."
    php artisan db:seed --force
fi

echo "Mengaktifkan cache konfigurasi..."
php artisan optimize

echo "Server berjalan pada host 0.0.0.0 port ${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
