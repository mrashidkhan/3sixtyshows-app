<?php
/**
 * fix-opcache.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-opcache.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$projectRoot = dirname(__DIR__);

// ── 1. Clear OPcache ──────────────────────────────────────────────────────────
echo "=== OPCACHE ===\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache reset\n";
} else {
    echo "ℹ️  OPcache not available or already disabled\n";
}

if (function_exists('opcache_invalidate')) {
    // Invalidate key files
    $filesToInvalidate = [
        $projectRoot . '/vendor/autoload.php',
        $projectRoot . '/vendor/composer/autoload_psr4.php',
        $projectRoot . '/vendor/composer/autoload_classmap.php',
        $projectRoot . '/vendor/composer/ClassLoader.php',
        $projectRoot . '/vendor/seatsio/seatsio-php/src/Region.php',
        $projectRoot . '/vendor/seatsio/seatsio-php/src/SeatsioClient.php',
        $projectRoot . '/bootstrap/app.php',
    ];
    foreach ($filesToInvalidate as $f) {
        if (file_exists($f)) {
            opcache_invalidate($f, true);
            echo "✅ Invalidated: " . basename($f) . "\n";
        }
    }
}
echo "\n";

// ── 2. Delete ALL bootstrap cache files ───────────────────────────────────────
echo "=== BOOTSTRAP CACHE ===\n";
$cacheDir = $projectRoot . '/bootstrap/cache/';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '*.php');
    foreach ($files as $file) {
        unlink($file);
        echo "✅ Deleted: " . basename($file) . "\n";
    }
    if (empty($files)) {
        echo "ℹ️  No cached files found\n";
    }
}
echo "\n";

// ── 3. Delete compiled views ──────────────────────────────────────────────────
echo "=== VIEW CACHE ===\n";
$viewCache = $projectRoot . '/storage/framework/views/';
if (is_dir($viewCache)) {
    $views = glob($viewCache . '*.php');
    $count = count($views);
    foreach ($views as $v) unlink($v);
    echo "✅ Deleted $count compiled view files\n";
}
echo "\n";

// ── 4. Touch the autoloader to force fresh load ───────────────────────────────
echo "=== TOUCH AUTOLOADER ===\n";
$autoload = $projectRoot . '/vendor/autoload.php';
touch($autoload);
echo "✅ Touched vendor/autoload.php (forces fresh load)\n\n";

// ── 5. Test the full Laravel bootstrap ───────────────────────────────────────
echo "=== LARAVEL BOOTSTRAP TEST ===\n";
try {
    // Simulate what Laravel does on every request
    require $projectRoot . '/vendor/autoload.php';
    echo "✅ autoload.php loaded\n";

    echo "Seatsio\\Region    : " . (class_exists('Seatsio\\Region')        ? "✅ found" : "❌ NOT found") . "\n";
    echo "Seatsio\\SeatsioClient: " . (class_exists('Seatsio\\SeatsioClient') ? "✅ found" : "❌ NOT found") . "\n";

    if (class_exists('Seatsio\\Region')) {
        $r = \Seatsio\Region::NA();
        echo "Region::NA() URL  : " . $r->url() . " ✅\n";
    }
} catch (\Throwable $e) {
    echo "❌ Bootstrap error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
echo "\n";

// ── 6. Check if there is a compiled.php or services.php that caches classes ──
echo "=== EXTRA CACHE FILES ===\n";
$extraFiles = [
    $projectRoot . '/bootstrap/cache/compiled.php',
    $projectRoot . '/storage/framework/cache/data',
];
foreach ($extraFiles as $f) {
    echo (file_exists($f) ? "EXISTS: " : "not found: ") . str_replace($projectRoot, '[root]', $f) . "\n";
}
echo "\n";

// ── 7. Check APP_ENV and APP_DEBUG in actual .env ─────────────────────────────
echo "=== ENVIRONMENT ===\n";
$envLines = file($projectRoot . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($envLines as $line) {
    foreach (['APP_ENV', 'APP_DEBUG', 'SEATSIO_REGION'] as $key) {
        if (str_starts_with($line, $key . '=')) {
            echo $line . "\n";
        }
    }
}
echo "\n";

echo "=== DONE ===\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo "Now visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";
echo "If still failing, the issue is opcache not clearing between requests.\n";
echo '</pre>';
