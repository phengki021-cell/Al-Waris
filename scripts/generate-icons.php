<?php

/**
 * Generator ikon PWA Al-Waris (dijalankan sekali; hasil disimpan ke public/icons/).
 * Jalankan: php scripts/generate-icons.php
 *
 * Desain: kotak penuh (maskable) hijau #123D32, huruf "W" putih, garis aksen emerald.
 * Render pada skala 4× lalu downscale agar antialias konsisten.
 */
$sizes = [192, 512];
$outDir = __DIR__.'/../public/icons';

if (! is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

foreach ($sizes as $size) {
    $scale = 4;
    $w = $size * $scale;

    $img = imagecreatetruecolor($w, $w);
    imagesavealpha($img, true);
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagealphablending($img, true);
    imagefill($img, 0, 0, $transparent);

    $green = colorFromHex($img, '#123D32');
    $accent = colorFromHex($img, '#D9C579');
    $white = colorFromHex($img, '#FFFFFF');

    // Kotak penuh (maskable) dengan sudut membulat.
    fillRoundedRect($img, 0, 0, $w, $w, (int) ($w * 0.16), $green);

    // Garis aksen horizontal di bawah huruf.
    $barH = (int) ($w * 0.045);
    fillRoundedRect($img, (int) ($w * 0.30), (int) ($w * 0.76), (int) ($w * 0.70), (int) ($w * 0.76 + $barH), (int) ($barH / 2), $accent);

    // Huruf "W" — poligon manual (tidak bergantung berkas font).
    drawW($img, $w, $white);

    $final = imagecreatetruecolor($size, $size);
    imagesavealpha($final, true);
    imagealphablending($final, true);
    imagefill($final, 0, 0, imagecolorallocatealpha($final, 0, 0, 0, 127));
    imagecopyresampled($final, $img, 0, 0, 0, 0, $size, $size, $w, $w);
    imagepng($final, "{$outDir}/icon-{$size}.png", 9);
    imagedestroy($final);
    imagedestroy($img);

    echo "OK icon-{$size}.png\n";
}

// Turunan: apple-touch-icon (harus solid, tanpa transparansi) + favicon PNG.
$src = imagecreatefrompng("{$outDir}/icon-512.png");

foreach ([[180, 'apple-touch-icon-180.png'], [48, 'favicon-48.png']] as [$s, $name]) {
    $dst = imagecreatetruecolor($s, $s);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $s, $s, imagesx($src), imagesy($src));
    imagepng($dst, "{$outDir}/{$name}", 9);
    imagedestroy($dst);
    echo "OK {$name}\n";
}

imagedestroy($src);

// Varian maskable: kanvas penuh tanpa sudut transparan (safe-zone Android).
foreach ($sizes as $size) {
    $scale = 4;
    $w = $size * $scale;

    $img = imagecreatetruecolor($w, $w);
    $green = colorFromHex($img, '#123D32');
    $accent = colorFromHex($img, '#D9C579');
    $white = colorFromHex($img, '#FFFFFF');

    imagefilledrectangle($img, 0, 0, $w, $w, $green);
    $barH = (int) ($w * 0.05);
    imagefilledrectangle($img, (int) ($w * 0.28), (int) ($w * 0.75), (int) ($w * 0.72), (int) ($w * 0.75 + $barH), $accent);
    drawW($img, $w, $white);

    $final = imagecreatetruecolor($size, $size);
    imagecopyresampled($final, $img, 0, 0, 0, 0, $size, $size, $w, $w);
    imagepng($final, "{$outDir}/maskable-{$size}.png", 9);
    imagedestroy($final);
    imagedestroy($img);

    echo "OK maskable-{$size}.png\n";
}

function colorFromHex($img, string $hex)
{
    $hex = ltrim($hex, '#');

    return imagecolorallocate($img, hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
}

/** Kotak membulat penuh: persegi + 4 lingkaran sudut. */
function fillRoundedRect($img, int $x1, int $y1, int $x2, int $y2, int $radius, $color): void
{
    $radius = min($radius, (int) (min($x2 - $x1, $y2 - $y1) / 2));

    imagefilledrectangle($img, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
    imagefilledrectangle($img, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);

    foreach ([[$x1, $y1, 180], [$x2, $y1, 270], [$x1, $y2, 90], [$x2, $y2, 0]] as [$cx, $cy, $start]) {
        imagefilledarc($img, $cx, $cy, $radius * 2, $radius * 2, $start, $start + 90, $color, IMG_ARC_PIE);
    }
}

/** Huruf W sebagai poligon putih di tengah kanvas. */
function drawW($img, int $w, $color): void
{
    $a = fn (float $t): int => (int) ($w * $t);

    // [x, y] searah jarum jam: bentuk W tebal (bar tetap seragam).
    $points = [
        [$a(0.26), $a(0.26)],
        [$a(0.35), $a(0.26)],
        [$a(0.435), $a(0.55)],
        [$a(0.50), $a(0.35)],
        [$a(0.565), $a(0.55)],
        [$a(0.65), $a(0.26)],
        [$a(0.74), $a(0.26)],
        [$a(0.625), $a(0.70)],
        [$a(0.545), $a(0.70)],
        [$a(0.50), $a(0.50)],
        [$a(0.455), $a(0.70)],
        [$a(0.375), $a(0.70)],
    ];

    $flat = [];
    foreach ($points as [$x, $y]) {
        $flat[] = $x;
        $flat[] = $y;
    }

    imagefilledpolygon($img, $flat, $color);
}
