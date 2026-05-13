<?php
/**
 * check-view.php
 * Upload to public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/check-view.php?secret=cv-2026
 * DELETE AFTER USE.
 */
if (!isset($_GET['secret']) || $_GET['secret'] !== 'cv-2026') {
    die('Unauthorized');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$root = dirname(__DIR__);

// Check blade files exist
$files = [
    'resources/views/layouts/master.blade.php',
    'resources/views/booking/reserved.blade.php',
    'resources/views/partials/header.blade.php',
    'resources/views/partials/footer.blade.php',
];

echo "=== BLADE FILES ===\n";
foreach ($files as $f) {
    $full = $root . '/' . $f;
    if (file_exists($full)) {
        echo "✅ $f\n";
    } else {
        echo "❌ MISSING: $f\n";
    }
}

echo "\n=== MASTER.BLADE.PHP CONTENT ===\n";
$master = $root . '/resources/views/layouts/master.blade.php';
if (file_exists($master)) {
    echo htmlspecialchars(file_get_contents($master));
}

echo "\n\n=== RESERVED.BLADE.PHP FIRST 20 LINES ===\n";
$reserved = $root . '/resources/views/booking/reserved.blade.php';
if (file_exists($reserved)) {
    $lines = file($reserved);
    foreach (array_slice($lines, 0, 20) as $i => $line) {
        echo ($i+1) . ": " . htmlspecialchars($line);
    }
}

echo "\n=== HEADER.BLADE.PHP - stack/yield lines ===\n";
$header = $root . '/resources/views/partials/header.blade.php';
if (file_exists($header)) {
    $lines = file($header);
    foreach ($lines as $i => $line) {
        if (str_contains($line, 'stack') || str_contains($line, 'yield') || str_contains($line, '</head>')) {
            echo ($i+1) . ": " . htmlspecialchars($line);
        }
    }
}

echo "\n=== VIEW CACHE ===\n";
$cacheDir = $root . '/storage/framework/views/';
$cached = glob($cacheDir . '*.php');
echo "Cached views: " . count($cached) . "\n";
// Delete them to force fresh compile
foreach ($cached as $c) {
    unlink($c);
}
echo "✅ Cleared " . count($cached) . " cached views\n";

echo "\n⚠️  DELETE: " . __FILE__ . "\n";
echo '</pre>';
