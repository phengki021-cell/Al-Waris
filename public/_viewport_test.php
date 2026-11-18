<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Al-Waris — Uji Viewport Responsif</title>
<style>
  body { margin: 0; background: #0f172a; color: #e2e8f0; font-family: system-ui, sans-serif; }
  .row { display: flex; flex-wrap: wrap; gap: 12px; padding: 12px; }
  .cell { border: 1px solid #334155; border-radius: 6px; overflow: hidden; }
  .cell > div { font-size: 11px; padding: 2px 6px; background: #1e293b; }
  iframe { border: 0; display: block; background: #fff; }
</style>
</head>
<body>
<?php
/* Dimensi uji wajib (persyaratan responsive 18). */
$sizes = [
    [360, 800], [375, 812], [390, 844], [412, 915],
    [768, 1024], [1024, 768], [1366, 768], [1440, 900],
];
$pages = isset($_GET['page']) ? [preg_replace('/[^a-z0-9\-]/', '', $_GET['page'])] : ['hitung-warisan', 'ahli-waris', 'bagian-warisan', 'cara-menghitung', 'referensi', 'riwayat', 'tentang'];
$base = 'http://127.0.0.1:8099/';
foreach ($pages as $page):
    foreach ($sizes as [$w, $h]): ?>
    <div class="cell">
        <div><?= htmlspecialchars($page) ?> — <?= $w ?>×<?= $h ?></div>
        <iframe src="<?= htmlspecialchars($base . $page) ?>" width="<?= $w ?>" height="<?= $h ?>"
                data-w="<?= $w ?>" data-page="<?= htmlspecialchars($page) ?>"></iframe>
    </div>
<?php endforeach; endforeach; ?>
</body>
</html>
