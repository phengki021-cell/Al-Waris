<?php

namespace App\Services;

/**
 * Penyedia data rujukan untuk modal edukasi: hadis shahih, yurisprudensi
 * Mahkamah Agung RI, pokok KUHPerdata (mode perdata), dan tautan jurnal
 * ilmiah. Semua data statis mengalir dari config/furudh-dalil.php.
 *
 * Kelas ini TIDAK menyentuh mesin hitung waris (App\Domain\Faraid).
 */
final class ReferenceService
{
    /** Hadis utama + rumusan kaidah terkait. @return array<string, mixed> */
    public function hadits(): array
    {
        return (array) config('furudh-dalil.hadits', []);
    }

    /**
     * Entry yurisprudensi sesuai daftar kunci (kunci tak dikenal dilewati).
     * Secara bawaan kunci default (MA No. 16 K/AG/2010) selalu ikut agar
     * modal edukasi memuat yurisprudensi ini; nonaktifkan dengan `$withDefaults`.
     *
     * @param  list<string>  $keys
     * @return list<array<string, mixed>>
     */
    public function yurisprudensi(array $keys = [], bool $withDefaults = true): array
    {
        return $this->resolve(
            config('furudh-dalil.yurisprudensi', []),
            $this->mergeKeys($withDefaults, $keys, ['MA-16-K-AG-2010']),
        );
    }

    /**
     * Entry jurnal ilmiah sesuai daftar kunci; dipakai untuk blok
     * "Baca Jurnal Ilmiah Terkait". Kunci default selalu ikut agar modal
     * edukasi memuat jurnal; nonaktifkan dengan `$withDefaults = false`.
     *
     * @param  list<string>  $keys
     * @return list<array<string, mixed>>
     */
    public function jurnal(array $keys = [], bool $withDefaults = true): array
    {
        return $this->resolve(
            config('furudh-dalil.jurnal', []),
            $this->mergeKeys($withDefaults, $keys, ['WASIAT-WAJIBAH-2010', 'FARAID-KHI-SCHOLAR', 'FARAID-KHI-SINTA']),
        );
    }

    /** Seluruh entry jurnal (urutan sesuai config) — dipakai pusat referensi. @return list<array<string, mixed>> */
    public function semuaJurnal(): array
    {
        return $this->resolve(config('furudh-dalil.jurnal', []), null);
    }

    /** Seluruh entry yurisprudensi (urutan sesuai config) @return list<array<string, mixed>> */
    public function semuaYurisprudensi(): array
    {
        return $this->resolve(config('furudh-dalil.yurisprudensi', []), null);
    }

    /** Data KUHPerdata (parafrase pasal + golongan + perbandingan). @return array<string, mixed> */
    public function kuhperdata(): array
    {
        return (array) config('furudh-dalil.kuhperdata', []);
    }

    /** Kartu edukasi topik kontemporer (tab "Kontemporer" kalkulator). @return list<array<string, mixed>> */
    public function kontemporer(): array
    {
        $cards = [];
        $config = config('furudh-dalil.kontemporer', []);

        if (! is_array($config)) {
            return $cards;
        }

        foreach ($config as $card) {
            if (is_array($card)) {
                $cards[] = $card;
            }
        }

        return $cards;
    }

    /**
     * Gabungkan kunci default (bila diminta) dengan kunci permintaan tanpa
     * duplikat; urutan: kunci default lebih dulu, baru kunci tambahan.
     *
     * @param  list<string>  $keys
     * @param  list<string>  $defaults
     * @return list<string>
     */
    private function mergeKeys(bool $withDefaults, array $keys, array $defaults): array
    {
        if (! $withDefaults) {
            return $keys;
        }

        $merged = $defaults;

        foreach ($keys as $key) {
            if (! in_array($key, $merged, true)) {
                $merged[] = $key;
            }
        }

        return $merged;
    }

    /**
     * Petakan isi config menjadi daftar entry berlabel kunci; kunci tak
     * dikenal maupun entry yang bukan array dilewati.
     *
     * @param  list<string>|null  $keys  null = seluruh entry mengikuti urutan config
     * @return list<array<string, mixed>>
     */
    private function resolve(mixed $config, ?array $keys): array
    {
        $list = [];

        if (! is_array($config)) {
            return $list;
        }

        if ($keys === null) {
            foreach ($config as $key => $entry) {
                if (is_array($entry)) {
                    $list[] = ['key' => $key] + $entry;
                }
            }

            return $list;
        }

        foreach ($keys as $key) {
            $entry = $config[$key] ?? null;

            if (is_array($entry)) {
                $list[] = ['key' => $key] + $entry;
            }
        }

        return $list;
    }
}
