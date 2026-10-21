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

/*
 * Banner ajakan instalasi PWA — muncul otomatis saat pertama kali membuka
 * halaman utama (markup ada di resources/views/pages/home.blade.php).
 * Preferensi disimpan di localStorage pada kunci al-waris-install-prompt:
 *   { choice: 'later' | 'installed', at: epochMillis }
 * 'installed' = tidak mengganggu lagi; 'later' = tawarkan ulang setelah 30 hari.
 */
const INSTALL_PREF_KEY = 'al-waris-install-prompt';
const INSTALL_REMIND_MS = 30 * 24 * 60 * 60 * 1000; // "Nanti Saja" = diingatkan lagi 30 hari

function readInstallPref() {
    try {
        return JSON.parse(window.localStorage.getItem(INSTALL_PREF_KEY) ?? 'null');
    } catch {
        return null;
    }
}

function saveInstallPref(choice) {
    try {
        window.localStorage.setItem(
            INSTALL_PREF_KEY,
            JSON.stringify({ choice, at: Date.now() }),
        );
    } catch {
        // Gagal menyimpan (mis. mode privat) — banner hanya berlaku di sesi ini.
    }
}

function shouldSuggestInstall() {
    if (document.querySelector('#pwa-install-banner') === null) {
        return false;
    }

    // Banner hanya untuk halaman utama (start_url '/').
    const isHomePage = window.location.pathname === '/'
        || window.location.pathname === '/index.php';

    if (! isHomePage) {
        return false;
    }

    // Sudah terpasang / dibuka sebagai aplikasi → tidak perlu ajakan.
    const standalone = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;

    if (standalone) {
        return false;
    }

    const pref = readInstallPref();

    if (pref) {
        if (pref.choice === 'installed') {
            return false;
        }

        if (pref.choice === 'later' && Date.now() - (pref.at ?? 0) < INSTALL_REMIND_MS) {
            return false;
        }
    }

    return true;
}

function showInstallBanner() {
    const banner = document.querySelector('#pwa-install-banner');

    if (banner !== null && banner.hidden) {
        banner.hidden = false;
    }
}

function hideInstallBanner() {
    const banner = document.querySelector('#pwa-install-banner');

    if (banner !== null) {
        banner.hidden = true;
    }
}

function tryAutoShow() {
    if (shouldSuggestInstall()) {
        window.setTimeout(showInstallBanner, 1500);
    }
}

// Chrome/Edge/Android: simpan event native agar banner bisa memanggil
// prompt() kapan pun pengguna menekan "Instal Sekarang".
let installPromptEvent = null;

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installPromptEvent = event;
    tryAutoShow();
});

window.addEventListener('DOMContentLoaded', () => {
    const banner = document.querySelector('#pwa-install-banner');

    if (banner === null) {
        return;
    }

    const accept = banner.querySelector('#pwa-install-accept');
    const later = banner.querySelector('#pwa-install-later');
    const close = banner.querySelector('#pwa-install-close');

    const dismiss = () => {
        saveInstallPref('later');
        hideInstallBanner();
    };

    accept?.addEventListener('click', async () => {
        if (installPromptEvent === null) {
            // Tidak ada prompt bawaan (mis. iOS Safari): simpan preferensi
            // "nanti" dan arahkan ke panduan instalasi manual.
            saveInstallPref('later');
            window.location.assign('/instalasi');

            return;
        }

        installPromptEvent.prompt();

        const choice = await installPromptEvent.userChoice;

        saveInstallPref(choice && choice.outcome === 'accepted' ? 'installed' : 'later');
        installPromptEvent = null;
        hideInstallBanner();
    });

    later?.addEventListener('click', dismiss);
    close?.addEventListener('click', dismiss);

    tryAutoShow();
});
