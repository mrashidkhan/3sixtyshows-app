<?php
/**
 * fix-path.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-path.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$projectRoot = dirname(__DIR__);
$psr4File    = $projectRoot . '/vendor/composer/autoload_psr4.php';

// ── 1. Show current Seatsio line ──────────────────────────────────────────────
echo "=== CURRENT PSR-4 FILE (Seatsio line) ===\n";
$content = file_get_contents($psr4File);
$lines = explode("\n", $content);
foreach ($lines as $line) {
    if (str_contains($line, 'Seatsio')) {
        echo "BEFORE: " . htmlspecialchars($line) . "\n";
    }
}
echo "\n";

// ── 2. Remove the bad manually-injected line and replace with correct one ─────
echo "=== FIXING PSR-4 FILE ===\n";

// Remove any existing Seatsio line (correct or incorrect)
$content = preg_replace("/.*'Seatsio\\\\\\\\'.+\n/", '', $content);

// Insert the correct line — using $vendorDir (not $baseDir) since seatsio is IN vendor
$correctLine = "  'Seatsio\\\\' => array(\$vendorDir . '/seatsio/seatsio-php/src'),\n";

// Insert before the closing );
$content = str_replace(
    "\n);",
    "\n" . $correctLine . ");",
    $content
);

file_put_contents($psr4File, $content);
echo "✅ Fixed autoload_psr4.php\n\n";

// ── 3. Show corrected line ────────────────────────────────────────────────────
echo "=== CORRECTED PSR-4 FILE (Seatsio line) ===\n";
$content = file_get_contents($psr4File);
$lines = explode("\n", $content);
foreach ($lines as $line) {
    if (str_contains($line, 'Seatsio')) {
        echo "AFTER: " . htmlspecialchars($line) . "\n";
    }
}
echo "\n";

// ── 4. Clear opcache ──────────────────────────────────────────────────────────
echo "=== CLEAR OPCACHE ===\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache reset\n";
}
if (function_exists('opcache_invalidate')) {
    opcache_invalidate($psr4File, true);
    opcache_invalidate($projectRoot . '/vendor/autoload.php', true);
    echo "✅ Key files invalidated\n";
}
echo "\n";

// ── 5. Verify the path now resolves correctly ─────────────────────────────────
echo "=== PATH VERIFICATION ===\n";
$psr4 = require $psr4File;
foreach ($psr4 as $ns => $paths) {
    if (str_contains($ns, 'Seatsio')) {
        foreach ($paths as $path) {
            echo "Resolved path : $path\n";
            echo "Dir exists    : " . (is_dir($path) ? "✅ YES" : "❌ NO") . "\n";
            echo "Region.php    : " . (file_exists($path . '/Region.php') ? "✅ YES" : "❌ NO") . "\n";
        }
    }
}
echo "\n";

// ── 6. Test class loading ─────────────────────────────────────────────────────
echo "=== CLASS LOAD TEST ===\n";
require $projectRoot . '/vendor/autoload.php';
echo "Seatsio\\Region    : " . (class_exists('Seatsio\\Region')        ? "✅ FOUND" : "❌ NOT FOUND") . "\n";
echo "Seatsio\\SeatsioClient: " . (class_exists('Seatsio\\SeatsioClient') ? "✅ FOUND" : "❌ NOT FOUND") . "\n";

if (class_exists('Seatsio\\Region')) {
    $r = \Seatsio\Region::NA();
    echo "Region::NA() URL  : " . $r->url() . " ✅\n";
}

echo "\n=== DONE ===\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo "Then visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";
echo '</pre>';
