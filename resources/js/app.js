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
 * Komponen input Rupiah realtime (Livewire + Alpine).
 * Tampilan memakai prefix "Rp" + pemisah titik ribuan saat diketik, sedangkan
 * model Livewire menyimpan RAW digit (mis. '500000000') sehingga mesin
 * pembagian faraid tetap menerima input numerik yang murni.
 * Input tampilan sengaja TANPA wire:model agar morph Livewire tidak
 * menimpa format pada setiap render.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('rupiahInput', (prop) => ({
        normalize(text) {
            return String(text ?? '')
                .replace(/\D+/g, '')
                .replace(/^0+(?=\d)/, '')
                .slice(0, 15);
        },

        /** Render tampilan: "Rp 500.000.000" dari raw digit. */
        paint(raw) {
            this.$el.value = raw === '' ? '' : `Rp ${Number(raw).toLocaleString('id-ID')}`;
        },

        /** x-effect — sinkron bila prop berubah dari sisi server (reset, dsb.). */
        sync(raw) {
            this.paint(String(raw ?? ''));
        },

        /** Event input: format instan + perbarui model dengan raw digit. */
        write(event) {
            const raw = this.normalize(event.target.value);

            this.paint(raw);
            this.$wire.set(prop, raw);
        },
    }));

    /*
     * Donut chart hasil pembagian (SVG stroke-dasharray, tanpa dependensi).
     * Slice dihitung dari persen masing-masing bagian; hover pada legend/kartu
     * atau pada segmen langsung menyorot slice yang bersangkutan (interaktif).
     */
    Alpine.data('donutChart', (rawSlices) => ({
        hovered: null,

        slices: (Array.isArray(rawSlices) ? rawSlices : []).map((slice) => ({
            label: String(slice.label ?? ''),
            value: Number(slice.value ?? 0),
            pct: Number(slice.pct ?? 0),
            color: String(slice.color ?? '#047857'),
        })),

        get total() {
            return this.slices.reduce((sum, slice) => sum + slice.value, 0);
        },

        /** Nilai terpilih untuk label tengah: slice yang di-hover atau total. */
        get centerValue() {
            if (this.hovered === null) {
                return this.total;
            }

            return this.slices[this.hovered]?.value ?? this.total;
        },

        get centerLabel() {
            if (this.hovered === null) {
                return 'Total dibagi';
            }

            return this.slices[this.hovered]?.label ?? 'Total dibagi';
        },

        get centerPct() {
            if (this.hovered === null) {
                return 100;
            }

            return this.slices[this.hovered]?.pct ?? 0;
        },

        dash(index) {
            const pct = Math.min(100, Math.max(0, this.slices[index].pct));

            return `${pct} ${100 - pct}`;
        },

        offset(index) {
            let accumulated = 0;

            for (let i = 0; i < index; i++) {
                accumulated += Math.min(100, Math.max(0, this.slices[i].pct));
            }

            return 25 - accumulated;
        },

        money(value) {
            return `Rp ${Number(value).toLocaleString('id-ID', { maximumFractionDigits: 0 })}`;
        },

        percent(value) {
            return `${Number(value).toLocaleString('id-ID', { maximumFractionDigits: 2 })}%`;
        },
    }));
});

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

/*
 * Popup instalasi PWA (tengah layar, situs luas) — tampil sekali pada kunjungan
 * pertama. Markup ada di resources/views/layouts/app.blade.php. Berbeda dengan
 * banner beranda (#pwa-install-banner) yang sasarannya halaman utama saja:
 * popup ini menjangkau halaman mana pun pada kunjungan pertama, dan dilewati
 * bila banner beranda memang sedang tampil (agar tidak muncul ganda).
 * Preferensi disimpan di localStorage pada kunci al-waris-install-popup ('seen').
 */
const INSTALL_POPUP_KEY = 'al-waris-install-popup';
const INSTALL_POPUP_DELAY = 2500;

function shouldShowInstallPopup() {
    const popup = document.querySelector('#pwa-install-popup');

    if (popup === null) {
        return false;
    }

    // Banner beranda sudah/kembali tampil → popup tidak perlu (hindari tumpang tindih).
    const banner = document.querySelector('#pwa-install-banner');

    if (banner !== null && ! banner.hidden) {
        return false;
    }

    // Halaman panduan instalasi sudah menjadi tujuan akhir ajakan.
    if (window.location.pathname.startsWith('/instalasi')) {
        return false;
    }

    // Sudah terpasang / dibuka sebagai aplikasi → tidak perlu ajakan.
    const standalone = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;

    if (standalone) {
        return false;
    }

    // Pengguna memilih "Terpasang" lewat banner beranda → hormati pilihan itu.
    const pref = readInstallPref();

    if (pref && pref.choice === 'installed') {
        return false;
    }

    try {
        if (window.localStorage.getItem(INSTALL_POPUP_KEY) === 'seen') {
            return false;
        }
    } catch {
        return false;
    }

    return true;
}

function showInstallPopup() {
    const popup = document.querySelector('#pwa-install-popup');

    if (popup === null || ! popup.hidden) {
        return;
    }

    try {
        // Ditandai saat ditampilkan agar pembaruan halaman/pindah tab dalam
        // kunjungan sama tidak mengulang popup.
        window.localStorage.setItem(INSTALL_POPUP_KEY, 'seen');
    } catch {
        // Gagal menyimpan (mis. mode privat) — popup hanya berlaku di sesi ini.
    }

    popup.hidden = false;
    popup.querySelector('#pwa-popup-accept')?.focus();
}

function hideInstallPopup() {
    const popup = document.querySelector('#pwa-install-popup');

    if (popup !== null) {
        popup.hidden = true;
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const popup = document.querySelector('#pwa-install-popup');

    if (popup === null) {
        return;
    }

    const accept = popup.querySelector('#pwa-popup-accept');
    const later = popup.querySelector('#pwa-popup-later');
    const closeButtons = popup.querySelectorAll('[data-pwa-popup-close]');

    const dismiss = () => {
        hideInstallPopup();
    };

    accept?.addEventListener('click', async () => {
        if (installPromptEvent === null) {
            // Tidak ada prompt bawaan (mis. iOS Safari): arahkan ke panduan
            // instalasi manual seperti tombol banner beranda.
            window.location.assign('/instalasi');

            return;
        }

        installPromptEvent.prompt();

        const choice = await installPromptEvent.userChoice;

        if (choice && choice.outcome === 'accepted') {
            try {
                // Aplikasi dipasang → jangan tawarkan lagi (sama dengan pref banner).
                window.localStorage.setItem(INSTALL_PREF_KEY, JSON.stringify({ choice: 'installed', at: Date.now() }));
            } catch {
                // abaikan: preferensi hanya melindungi kunjungan berikutnya.
            }
        }

        installPromptEvent = null;
        dismiss();
    });

    later?.addEventListener('click', dismiss);

    // Tombol silang menutup toast (toast mengambang, tanpa latar belakang gelap).
    closeButtons.forEach((el) => el.addEventListener('click', dismiss));

    window.addEventListener('keydown', (keyEvent) => {
        if (keyEvent.key === 'Escape' && ! popup.hidden) {
            dismiss();
        }
    });

    if (shouldShowInstallPopup()) {
        window.setTimeout(showInstallPopup, INSTALL_POPUP_DELAY);
    }
});

/* =========================================================================
 * Design-system runtime — Reveal on scroll, angka berhitung, parallax ringan.
 * Semua berbasis atribut data-* di blade + utilitas u-* di resources/css/app.css.
 * Dibuat aman untuk Livewire (morph): observer dipasang sekali, hanya pada
 * elemen yang belum diproses; respects prefers-reduced-motion.
 * ========================================================================= */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

let revealObserver = null;
let countObserver = null;

function enhanceReveal(root = document) {
    if (reduceMotion.matches) {
        root.querySelectorAll('[data-reveal]:not(.is-revealed)').forEach((el) => {
            el.classList.add('is-revealed');
        });

        return;
    }

    if (!('IntersectionObserver' in window)) {
        root.querySelectorAll('[data-reveal]:not(.is-revealed)').forEach((el) => {
            el.classList.add('is-revealed');
        });

        return;
    }

    revealObserver ??= new IntersectionObserver((entries) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                revealObserver?.unobserve(entry.target);
            }
        }
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    root.querySelectorAll('[data-reveal]:not(.is-revealed)').forEach((el) => {
        revealObserver?.observe(el);
    });
}

/**
 * Angka berhitung: <span data-count="12">12</span>.
 * Non-reduced-motion: mulai dari 0 dan bergerak ke target; hasil akhir selalu
 * sama dengan nilai asli (aman terhadap Livewire morph yang hanya mengganti teks).
 */
function enhanceCounters(root = document) {
    const counters = root.querySelectorAll('[data-count]');

    if (counters.length === 0) {
        return;
    }

    if (reduceMotion.matches || !('IntersectionObserver' in window)) {
        return; // biarkan nilai asli tampil apa adanya
    }

    counters.forEach((el) => {
        if (el.dataset.counted === '1') {
            return;
        }

        el.dataset.counted = '1';

        const target = Number.parseInt(el.dataset.count ?? '', 10);
        const fallback = Number.parseInt(el.textContent ?? '', 10);
        const finalValue = Number.isFinite(target) ? target : fallback;

        if (!Number.isFinite(finalValue) || finalValue <= 0) {
            return;
        }

        el.textContent = '0';

        countObserver ??= new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                countObserver?.unobserve(entry.target);

                const startAt = performance.now();
                const durationMs = Math.min(1400, 350 + finalValue * 60);

                const tick = (now) => {
                    const progress = Math.min(1, (now - startAt) / durationMs);
                    const eased = 1 - Math.pow(1 - progress, 3);

                    el.textContent = Math.max(1, Math.round(finalValue * eased)).toLocaleString('id-ID');

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = finalValue.toLocaleString('id-ID');
                    }
                };

                requestAnimationFrame(tick);
            }
        }, { threshold: 0.4 });

        countObserver?.observe(el);
    });
}

/**
 * Parallax ringan: elemen dengan [data-parallax="0.08"] bergeser sedikit
 * saat scroll. Mengikuti grup [data-parallax-group] terdekat agar hanya
 * aktif saat bagian tersebut terlihat di viewport.
 */
function setupParallax() {
    const items = [...document.querySelectorAll('[data-parallax]')];

    if (items.length === 0 || reduceMotion.matches || !('requestAnimationFrame' in window)) {
        return;
    }

    let ticking = false;

    const update = () => {
        ticking = false;

        for (const el of items) {
            const group = el.closest('[data-parallax-group]') ?? el.parentElement;

            if (group === null) {
                continue;
            }

            const rect = group.getBoundingClientRect();

            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                continue;
            }

            const factor = Number.parseFloat(el.dataset.parallax ?? '0') || 0;
            const progress = (rect.top + rect.height / 2 - window.innerHeight / 2) / window.innerHeight;

            el.style.transform = `translate3d(0, ${(progress * factor * 400).toFixed(2)}px, 0)`;
        }
    };

    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    };

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
}

/** Pasang semua enhancement; dipanggil pada load pertama. */
document.addEventListener('DOMContentLoaded', () => {
    enhanceReveal();
    enhanceCounters();
    setupParallax();
});

/** Saat Livewire memasang/mengganti komponen, aktifkan ulang elemen baru. */
document.addEventListener('livewire:navigated', () => {
    enhanceReveal();
    enhanceCounters();
});
