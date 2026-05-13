<?php
/**
 * diagnose.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/diagnose.php?secret=diag-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'diag-2026') {
    die('Unauthorized. Add ?secret=diag-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

// ── 1. Paths ──────────────────────────────────────────────────────────────────
$publicDir  = __DIR__;                    // this file's directory (public/)
$projectRoot = dirname($publicDir);       // one level up = Laravel root

echo "=== PATHS ===\n";
echo "This file   : " . __FILE__ . "\n";
echo "Public dir  : $publicDir\n";
echo "Project root: $projectRoot\n\n";

// ── 2. PHP version ────────────────────────────────────────────────────────────
echo "=== PHP ===\n";
echo "Version: " . PHP_VERSION . "\n";
echo "SAPI   : " . php_sapi_name() . "\n\n";

// ── 3. Key files exist? ───────────────────────────────────────────────────────
echo "=== KEY FILES ===\n";
$files = [
    $projectRoot . '/composer.json',
    $projectRoot . '/composer.lock',
    $projectRoot . '/vendor/autoload.php',
    $projectRoot . '/vendor/seatsio/seatsio-php/src/Region.php',
    $projectRoot . '/vendor/seatsio/seatsio-php/src/SeatsioClient.php',
    $projectRoot . '/.env',
];
foreach ($files as $f) {
    $short = str_replace($projectRoot, '[root]', $f);
    echo ($file_exists = file_exists($f) ? "✅" : "❌") . " $short\n";
}
echo "\n";

// ── 4. Composer locations ─────────────────────────────────────────────────────
echo "=== COMPOSER LOCATIONS ===\n";
$composerPaths = [
    '/usr/local/bin/composer',
    '/usr/bin/composer',
    '/opt/cpanel/composer/bin/composer',
    $projectRoot . '/composer.phar',
    $publicDir . '/composer.phar',
];
foreach ($composerPaths as $p) {
    echo (file_exists($p) ? "✅" : "❌") . " $p\n";
}
echo "\n";

// ── 5. Can exec() run commands? ───────────────────────────────────────────────
echo "=== EXEC AVAILABILITY ===\n";
$disabled = explode(',', ini_get('disable_functions'));
$disabled = array_map('trim', $disabled);
echo "exec disabled    : " . (in_array('exec', $disabled)    ? "YES ❌" : "NO ✅") . "\n";
echo "shell_exec disabled: " . (in_array('shell_exec', $disabled) ? "YES ❌" : "NO ✅") . "\n";
echo "system disabled  : " . (in_array('system', $disabled)  ? "YES ❌" : "NO ✅") . "\n\n";

// ── 6. Try running composer directly ─────────────────────────────────────────
echo "=== COMPOSER TEST ===\n";
if (!in_array('exec', $disabled)) {
    $output = [];
    exec('composer --version 2>&1', $output);
    echo "composer --version: " . implode(' ', $output) . "\n";

    $output2 = [];
    exec('php -r "echo PHP_VERSION;" 2>&1', $output2);
    echo "php inline test   : " . implode(' ', $output2) . "\n";
} else {
    echo "exec() is disabled — cannot run composer via exec()\n";
}
echo "\n";

// ── 7. .env contents (safe keys only) ────────────────────────────────────────
echo "=== .ENV CHECK (safe keys only) ===\n";
$envFile = $projectRoot . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $safeKeys = ['APP_ENV', 'APP_URL', 'APP_DEBUG', 'SEATSIO_REGION', 'DB_CONNECTION'];
    foreach ($lines as $line) {
        if (str_starts_with($line, '#')) continue;
        foreach ($safeKeys as $key) {
            if (str_starts_with($line, $key . '=')) {
                echo $line . "\n";
            }
        }
    }
    // Just confirm secrets exist without showing values
    $secretKeys = ['SEATSIO_SECRET_KEY', 'SEATSIO_PUBLIC_KEY'];
    foreach ($lines as $line) {
        foreach ($secretKeys as $key) {
            if (str_starts_with($line, $key . '=')) {
                $val = trim(explode('=', $line, 2)[1]);
                echo $key . "=" . (empty($val) ? "❌ EMPTY" : "✅ SET (" . strlen($val) . " chars)") . "\n";
            }
        }
    }
} else {
    echo "❌ .env file NOT found at $envFile\n";
}
echo "\n";

// ── 8. vendor/seatsio contents ────────────────────────────────────────────────
echo "=== VENDOR/SEATSIO CONTENTS ===\n";
$seatsioDir = $projectRoot . '/vendor/seatsio';
if (is_dir($seatsioDir)) {
    $items = scandir($seatsioDir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        echo "  " . $item . "\n";
    }
} else {
    echo "❌ vendor/seatsio directory does not exist\n";
    echo "   This means composer install did not run or failed silently.\n";
}
echo "\n";

// ── 9. Autoloader test ────────────────────────────────────────────────────────
echo "=== AUTOLOADER TEST ===\n";
$autoload = $projectRoot . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
    echo "✅ vendor/autoload.php loaded\n";
    echo "Seatsio\\Region exists: " . (class_exists('Seatsio\\Region') ? "✅ YES" : "❌ NO") . "\n";
    echo "Seatsio\\SeatsioClient exists: " . (class_exists('Seatsio\\SeatsioClient') ? "✅ YES" : "❌ NO") . "\n";
} else {
    echo "❌ vendor/autoload.php not found\n";
}
echo "\n";

echo "=== DONE ===\n";
echo "Share this output to diagnose the issue.\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo '</pre>';
