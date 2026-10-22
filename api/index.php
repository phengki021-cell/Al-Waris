<?php

/**
 * Entry serverless untuk deployment Vercel (runtime vercel-php).
 * Selain menautkan front controller Laravel, menyiapkan database SQLite
 * yang dapat ditulis (/tmp) dari bundel aplikasi pada cold start.
 */

$bundleDb = __DIR__ . '/../database/database.sqlite';
$runtimeDb = '/tmp/database.sqlite';

if (! file_exists($runtimeDb) && file_exists($bundleDb)) {
    @copy($bundleDb, $runtimeDb);
}

require __DIR__ . '/../public/index.php';
