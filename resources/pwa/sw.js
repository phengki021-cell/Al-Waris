/*
 * Service Worker Al-Waris — strategi cache minimal & aman.
 *
 * PRINSIP:
 * 1. Hanya aset statis yang di-cache: berkas build Vite (ber-hash, aman caching),
 *    ikon, dan halaman offline.
 * 2. TIDAK ada konten berisi data pengguna yang di-cache: halaman hasil
 *    kalkulasi, halaman riwayat, dan seluruh request Livewire selalu lewat
 *    jaringan (network-only, tanpa cache).
 * 3. Halaman HTML edukasi: network-first; bila jaringan gagal, tampilkan
 *    halaman offline sebagai fallback.
 * 4. Pembaruan: versi cache dinaikkan (CACHE_VERSION); cache lama dibuang saat
 *    aktivasi; halaman dapat memaksa perbarui lewat pesan SKIP_WAITING.
 */

const CACHE_VERSION = 'al-waris-v1';
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/maskable-192.png',
    '/icons/maskable-512.png',
    '/favicon.ico',
    '/icons/favicon-48.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_VERSION);
            await cache.addAll(PRECACHE_ASSETS);
        })(),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        (async () => {
            const names = await caches.keys();

            await Promise.all(
                names.map((name) => (name !== CACHE_VERSION ? caches.delete(name) : null)),
            );

            await self.clients.claim();
        })(),
    );
});

self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

function isStaticAsset(request) {
    const url = new URL(request.url);

    // Aset build Vite (ber-hash) + font + ikon + favicon.
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/icons/') || url.pathname.startsWith('/fonts/')) {
        return true;
    }

    return /\.(?:css|js|woff2?|png|svg|jpg|jpeg|webp|ico)$/.test(url.pathname);
}

function isDynamicPage(url) {
    const dynamicPaths = [
        '/hitung-warisan',
        '/riwayat',
        '/livewire/update',
    ];

    return dynamicPaths.some((p) => url.pathname.startsWith(p));
}

self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Mutasi & non-GET: tidak pernah di-cache.
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    //lintas origin (CDN font) — passthrough network, jangan cache di sini.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Halaman/endpoint dinamis berpotensi berisi data pribadi → network-only.
    if (isDynamicPage(url) || url.pathname.includes('livewire')) {
        event.respondWith(
            fetch(request).catch(async () => {
                if (request.mode === 'navigate') {
                    const cache = await caches.open(CACHE_VERSION);

                    return cache.match(OFFLINE_URL);
                }

                return new Response('Offline', { status: 503 });
            }),
        );

        return;
    }

    // Aset statis: cache-first (disimpan ke cache saat pertama diambil).
    if (isStaticAsset(request)) {
        event.respondWith(
            (async () => {
                const cache = await caches.open(CACHE_VERSION);
                const cached = await cache.match(request);

                if (cached) {
                    return cached;
                }

                const response = await fetch(request);

                if (response.ok) {
                    cache.put(request, response.clone());
                }

                return response;
            })(),
        );

        return;
    }

    // Navigasi HTML: network-first, fallback offline (HTML tidak di-cache).
    if (request.mode === 'navigate') {
        event.respondWith(
            (async () => {
                try {
                    return await fetch(request);
                } catch {
                    const cache = await caches.open(CACHE_VERSION);

                    return cache.match(OFFLINE_URL);
                }
            })(),
        );
    }
});
