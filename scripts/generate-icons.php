<?php

/**
 * Generator ikon PWA Al-Waris — semua ikon diturunkan dari logo resmi.
 * Jalankan: php scripts/generate-icons.php
 *
 * Sumber: resources/brand/logo-al-waris.jpg (logo persegi, JPEG).
 * Output di public/icons/:
 *   icon-192/512 (full-bleed), maskable-192/512 (logo 78% di atas latar
 *   mengikuti warna tepi logo — zona aman Android), apple-touch-icon-180,
 *   favicon-48, dan public/favicon.ico (tray PNG-in-ICO 48px).
 */
$sourcePath = __DIR__.'/../resources/brand/logo-al-waris.jpg';

if (! is_file($sourcePath)) {
    fwrite(STDERR, "Sumber logo tidak ditemukan: {$sourcePath}\n");
    exit(1);
}

$outDir = __DIR__.'/../public/icons';
if (! is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

$source = imagecreatefromjpeg($sourcePath);
$sw = imagesx($source);
$sh = imagesy($source);
$side = min($sw, $sh); // logo persegi — buang tumpang tindih bila tak persegi sempurna

// Warna tepi logo → latar maskable/apple (diambil dari pojok kiri atas).
$edgeColor = imagecolorat($source, 4, 4);
$bg = ['r' => ($edgeColor >> 16) & 0xFF, 'g' => ($edgeColor >> 8) & 0xFF, 'b' => $edgeColor & 0xFF];

/**
 * Ukuran [panjang_sisi, nama_berkas, pengisi_latar('bleed'|'solid')]
 * 'bleed' = logo memenuhi kanvas; 'solid' = logo 78% di tengah (safe zone maskable).
 */
$targets = [
    [192, 'icon-192.png', 'bleed'],
    [512, 'icon-512.png', 'bleed'],
    [192, 'maskable-192.png', 'solid'],
    [512, 'maskable-512.png', 'solid'],
    [180, 'apple-touch-icon-180.png', 'solid'], // iOS menutup sudut sendiri
    [48, 'favicon-48.png', 'solid'],
];

$pngBytes = [];

foreach ($targets as [$size, $name, $mode]) {
    $canvas = imagecreatetruecolor($size, $size);
    // Latar solid mengikuti warna tepi (jpeg tak punya alpha).
    $bgColor = imagecolorallocate($canvas, $bg['r'], $bg['g'], $bg['b']);
    imagefill($canvas, 0, 0, $bgColor);

    if ($mode === 'bleed') {
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $size, $size, $side, $side);
        $bytes = null; // simpan dari latar langsung
        ob_start();
        imagepng($canvas, null, 9);
        $pngBytes[$name] = ob_get_clean();
    } else {
        // Zona aman maskable: konten ≤ 80% dari dimensi kanvas.
        $content = (int) floor($size * 0.78);
        $offset = (int) floor(($size - $content) / 2);
        imagecopyresampled($canvas, $source, $offset, $offset, 0, 0, $content, $content, $side, $side);

        ob_start();
        imagepng($canvas, null, 9);
        $pngBytes[$name] = ob_get_clean();
    }

    file_put_contents("{$outDir}/{$name}", $pngBytes[$name]);
    imagedestroy($canvas);
    echo "OK {$name}\n";
}

imagedestroy($source);

// favicon.ico — kontainer ICO berisi PNG 48×48 (format PNG-in-ICO, Vista+).
$icoPath = __DIR__.'/../public/favicon.ico';
$png48 = $pngBytes['favicon-48.png'];
$ico = pack('vvv', 0, 1, 1). // ICONDIR: reserved, type=icon, count=1
    pack('CCCCvvVV',
        48, 48, 0, // lebar, tinggi, palet
        0, 1, 32, // reserved, planes, bpp
        strlen($png48),
        6 + 16 // offset data
    ).$png48;
file_put_contents($icoPath, $ico);
echo 'OK favicon.ico ('.strlen($ico)." bytes)\n";
