<?php

use App\Domain\Faraid\FaraidEngine;

/**
 * TAHAP 2 — Audit kaidah faraid & regresi bug (mesin App\Domain\Faraid).
 *
 * Semua hasil acuan di bawah adalah kasus faraid klasik yang tidak berbeda
 * pendapat (amal ummi), diverifikasi terhadap teks kitab faraid baku.
 * Kasus yang memuat beda pendapat diberi anotasi dan hanya mengecek
 * status/label, bukan angka pemaksaan.
 */
beforeEach(fn () => $this->engine = new FaraidEngine);

/** Ambil pecahan satu ahli waris dari hasil valid (null bila tidak menerima). */
function shareOf($result, string $code): ?array
{
    return collect($result->shares)->firstWhere('code', $code);
}

test('regresi: kakek TIDAK menerima bagian bila ayah masih ada (bug Tahap 2)', function () {
    $r = $this->engine->calculate(['grandfather' => 1, 'father' => 1, 'daughters' => 1]);

    expect($r->status)->toBe('valid');
    expect(shareOf($r, 'grandfather'))->toBeNull();          // kakek tidak mewarisi bersama ayah
    expect(shareOf($r, 'daughter')['text'])->toBe('1/2');    // fard anak perempuan tetap
    expect(shareOf($r, 'father')['text'])->toBe('1/2');      // ayah: 1/6 + sisa 1/3
    // Total pecahan = 1 (tidak ada bagian melayang/terdobel).
    expect(collect($r->shares)->sum(fn ($s) => $s['n'] / $s['d']))->toEqualWithDelta(1.0, 1e-12);
});

test('regresi: kakek + ayah + ibu + anak pr — kakek terhalang, total eksak', function () {
    $r = $this->engine->calculate(['grandfather' => 1, 'father' => 1, 'mother' => 1, 'daughters' => 1]);

    expect($r->status)->toBe('valid');
    expect(shareOf($r, 'grandfather'))->toBeNull();
    expect(shareOf($r, 'mother')['text'])->toBe('1/6');
    expect(shareOf($r, 'father')['text'])->toBe('1/3'); // 1/6 fard + 1/6 sisa
    expect(shareOf($r, 'daughter')['text'])->toBe('1/2');
    expect(collect($r->shares)->sum(fn ($s) => $s['n'] / $s['d']))->toEqualWithDelta(1.0, 1e-12);
});

test('regresi: kakek terhalang ayah tetap muncul dengan alasan di daftar hijab', function () {
    $r = $this->engine->calculate(['grandfather' => 1, 'father' => 1, 'daughters' => 1]);

    $blocked = collect($r->blocked)->firstWhere('code', 'grandfather');

    expect($blocked)->not->toBeNull()
        ->and($blocked['reason'])->toContain('ayah');
});

test('kategori 1 — suami dengan anak: suami 1/4, radd ke anak pr (3/4 total, 3/8 per orang)', function () {
    $r = $this->engine->calculate(['husband' => 1, 'daughters' => 2]);

    expect($r->status)->toBe('valid')
        ->and($r->method)->toBe('radd')
        ->and(shareOf($r, 'husband')['text'])->toBe('1/4')
        ->and(shareOf($r, 'daughter')['text'])->toBe('3/4')
        ->and(shareOf($r, 'daughter')['per_person_text'])->toBe('3/8');
});

test('kategori 2 — istri dengan anak: 1/8 + asabah 2:1 pada sisa 7/8', function () {
    $r = $this->engine->calculate(['wife_count' => 1, 'sons' => 2, 'daughters' => 1]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'wife')['text'])->toBe('1/8')
        ->and(shareOf($r, 'son')['text'])->toBe('7/10')          // 28/40 total dua anak
        ->and(shareOf($r, 'son')['per_person_text'])->toBe('7/20') // 14/40 per anak = 2 unit dari 5
        ->and(shareOf($r, 'daughter')['text'])->toBe('7/40');
});

test('kategori 3 — ayah & ibu susunan klasik: ayah menyerap sisa', function () {
    $r = $this->engine->calculate(['father' => 1, 'mother' => 1]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'father')['text'])->toBe('2/3')
        ->and(shareOf($r, 'mother')['text'])->toBe('1/3');
});

test('kategori 3b — ayah + ibu + anak pr: ibu turun ke 1/6 (hijab nuqsan), ayah 1/6+sisa', function () {
    $r = $this->engine->calculate(['father' => 1, 'mother' => 1, 'daughters' => 2]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'mother')['text'])->toBe('1/6')
        ->and(shareOf($r, 'daughter')['text'])->toBe('2/3')
        ->and(shareOf($r, 'father')['text'])->toBe('1/6'); // sisa = 0 → tidak ada penyerapan
    expect(collect($r->shares)->sum(fn ($s) => $s['n'] / $s['d']))->toEqualWithDelta(1.0, 1e-12);
});

test('kategori 4 — anak laki & perempuan bersama (2:1)', function () {
    $r = $this->engine->calculate(['sons' => 1, 'daughters' => 1]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'son')['text'])->toBe('2/3')
        ->and(shareOf($r, 'daughter')['text'])->toBe('1/3');
});

test('kategori 5 — asabah binafsihi: anak laki-laki sendiri kapan pun', function () {
    expect(shareOf($this->engine->calculate(['sons' => 1]), 'son')['text'])->toBe('1');
    expect(shareOf($this->engine->calculate(['sons' => 2]), 'son')['text'])->toBe('1')
        ->and(shareOf($this->engine->calculate(['sons' => 2]), 'son')['per_person_text'])->toBe('1/2');
});

test('kategori 6 — asabah bil ghair: anak pr 1/2 menyetir saudara pr 1/2', function () {
    $r = $this->engine->calculate(['daughters' => 1, 'sisters' => 1]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'daughter')['text'])->toBe('1/2')
        ->and(shareOf($r, 'sister')['text'])->toBe('1/2');
});

test("kategori 7 — asabah ma'al ghair: 2 anak pr (2/3) + saudara pr menyerap sisa 1/3", function () {
    $r = $this->engine->calculate(['daughters' => 2, 'sisters' => 1]);

    expect($r->status)->toBe('valid')
        ->and(shareOf($r, 'daughter')['text'])->toBe('2/3')
        ->and(shareOf($r, 'sister')['text'])->toBe('1/3');
});

test("kategori 7b — 'aul klasik: suami + 2 saudara pr (1/2 + 2/3 = 7/6 → asal 6→7)", function () {
    $r = $this->engine->calculate(['husband' => 1, 'sisters' => 2]);

    expect($r->status)->toBe('valid')
        ->and($r->method)->toBe('aul')
        ->and(shareOf($r, 'husband')['text'])->toBe('3/7')
        ->and(shareOf($r, 'sister')['per_person_text'])->toBe('2/7');
});

test('kategori 8 — hijab nuqsan istri: 1/4 → 1/8 karena keturunan', function () {
    $r = $this->engine->calculate(['wife_count' => 1, 'sons' => 2]);

    expect(shareOf($r, 'wife')['text'])->toBe('1/8');
});

test('kategori 8b — hijab hirman: ayah menghalangi seluruh saudara', function () {
    $r = $this->engine->calculate(['father' => 1, 'daughters' => 1, 'sisters' => 1]);

    expect(shareOf($r, 'sister'))->toBeNull()
        ->and(shareOf($r, 'father')['text'])->toBe('1/2')
        ->and(collect($r->blocked)->firstWhere('code', 'sister'))->not->toBeNull();
});

test('kategori 9 — radd: ibu + 2 anak pr → 1/5 dan 4/5 (2/5 per orang)', function () {
    $r = $this->engine->calculate(['mother' => 1, 'daughters' => 2]);

    expect($r->method)->toBe('radd')
        ->and(shareOf($r, 'mother')['text'])->toBe('1/5')
        ->and(shareOf($r, 'daughter')['per_person_text'])->toBe('2/5');
});

test('kategori 11 — jamak: dua istri & tiga anak laki tetap eksak', function () {
    $r = $this->engine->calculate(['wife_count' => 2, 'sons' => 3]);

    expect(shareOf($r, 'wife')['text'])->toBe('1/8')
        ->and(shareOf($r, 'wife')['count'])->toBe(2)
        ->and(shareOf($r, 'wife')['per_person_text'])->toBe('1/16')
        ->and(collect($r->shares)->map(fn ($s) => $s['code'])->values()->all())
        ->toEqual(['wife', 'son']);
    expect(collect($r->shares)->sum(fn ($s) => $s['n'] / $s['d']))->toEqualWithDelta(1.0, 1e-12);
});

test('kategori 12 — input tidak sah: negatif/desimal pasangan ganda ditolak, bukan hasil palsu', function () {
    expect($this->engine->calculate(['sons' => -1])->status)->toBe('error');
    expect($this->engine->calculate(['sons' => 2.5])->status)->toBe('error');
    expect($this->engine->calculate(['husband' => 1, 'wife_count' => 1])->status)->toBe('error');
    expect($this->engine->calculate([])->status)->toBe('error');
});

test('kategori 14 — di luar cakupan: umariyyatan & dzawil arham tidak dipaksakan', function () {
    expect($this->engine->calculate(['grandfather' => 1, 'grandsons' => 1])->status)->toBe('not_supported');
    expect($this->engine->calculate(['grandfather' => 1, 'brothers' => 1])->status)->toBe('not_supported');
    expect($this->engine->calculate(['husband' => 1])->status)->toBe('not_supported');
});
