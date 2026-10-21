/*
 * Al-Waris — entry point JS.
 * Livewire 4 memuat skripnya sendiri otomatis; berkas ini hanya mendaftarkan
 * service worker PWA (Tahap 4).
 */

// Pendaftaran service worker hanya di lingkungan aman (HTTPS) atau localhost
// saat pengembangan. Tidak berjalan bila browser tidak mendukung.
if ('serviceWorker' in navigator) {
    const isLocalhost = ['localhost', '127.0.0.1', '[::1]'].includes(window.location.hostname);
    const isSecure = window.location.protocol === 'https:' || isLocalhost;

    if (isSecure) {
        window.addEventListener('load', () => {
            navigator.serviceWorker
                .register('/sw.js', { scope: '/' })
                .then((registration) => {
                    // Saat ada worker baru menunggu, minta langsung aktif agar
                    // cache versi terbaru segera dipakai (pembaruan aman).
                    if (registration.waiting && navigator.serviceWorker.controller) {
                        registration.waiting.postMessage('SKIP_WAITING');
                    }

                    registration.addEventListener('updatefound', () => {
                        const installing = registration.installing;

                        if (! installing) {
                            return;
                        }

                        installing.addEventListener('statechange', () => {
                            if (
                                installing.state === 'installed'
                                && navigator.serviceWorker.controller
                            ) {
                                installing.postMessage('SKIP_WAITING');
                            }
                        });
                    });
                })
                .catch(() => {
                    // Gagal daftar (mis. offline pertama kali) — aplikasi tetap jalan normal.
                });
        });

        // Ketika worker baru mengambil alih (versi cache baru aktif), muat ulang
        // sekali agar halaman memakai aset terbaru.
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (refreshing) {
                return;
            }

            refreshing = true;
            window.location.reload();
        });
    }
}
