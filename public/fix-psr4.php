<?php
/**
 * fix-psr4.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-psr4.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$projectRoot = dirname(__DIR__);

// ── 1. Check current PSR-4 map ────────────────────────────────────────────────
echo "=== CURRENT PSR-4 MAP (Seatsio entries) ===\n";
$psr4File = $projectRoot . '/vendor/composer/autoload_psr4.php';
$psr4 = require $psr4File;

$seatsioFound = false;
foreach ($psr4 as $namespace => $paths) {
    if (str_contains($namespace, 'Seatsio') || str_contains($namespace, 'seatsio')) {
        echo "FOUND: '$namespace' => " . implode(', ', $paths) . "\n";
        $seatsioFound = true;
    }
}
if (!$seatsioFound) {
    echo "❌ Seatsio namespace NOT in PSR-4 map\n";
}
echo "\n";

// ── 2. Check seatsio composer.json to see how it registers autoloading ────────
echo "=== SEATSIO PACKAGE COMPOSER.JSON ===\n";
$seatsioComposer = $projectRoot . '/vendor/seatsio/seatsio-php/composer.json';
if (file_exists($seatsioComposer)) {
    $data = json_decode(file_get_contents($seatsioComposer), true);
    echo "Autoload section:\n";
    echo json_encode($data['autoload'] ?? 'NOT FOUND', JSON_PRETTY_PRINT) . "\n";
} else {
    echo "❌ seatsio composer.json not found\n";
}
echo "\n";

// ── 3. Manually add Seatsio to PSR-4 map if missing ──────────────────────────
echo "=== FIX PSR-4 MAP ===\n";
$seatsioSrcPath = $projectRoot . '/vendor/seatsio/seatsio-php/src/';

if (!$seatsioFound && is_dir($seatsioSrcPath)) {
    // Read the current autoload_psr4.php content
    $content = file_get_contents($psr4File);

    // Add Seatsio namespace before the closing );
    $entry = "\n  'Seatsio\\\\' => array(\$baseDir . '/vendor/seatsio/seatsio-php/src'),";

    // Insert before the closing ); of the return array
    $content = str_replace(
        "\n);",
        $entry . "\n);",
        $content
    );

    file_put_contents($psr4File, $content);
    echo "✅ Manually added Seatsio\\ to autoload_psr4.php\n\n";
} elseif ($seatsioFound) {
    echo "ℹ️  Seatsio already in PSR-4 map — no fix needed\n\n";
} else {
    echo "❌ seatsio/src directory not found at: $seatsioSrcPath\n\n";
}

// ── 4. Also check autoload_namespaces.php ────────────────────────────────────
echo "=== CHECK AUTOLOAD_NAMESPACES.PHP ===\n";
$nsFile = $projectRoot . '/vendor/composer/autoload_namespaces.php';
if (file_exists($nsFile)) {
    $ns = require $nsFile;
    $found = array_filter(array_keys($ns), fn($k) => str_contains($k, 'Seatsio'));
    echo count($found) > 0
        ? "Found in namespaces: " . implode(', ', $found) . "\n"
        : "Not in autoload_namespaces.php\n";
}
echo "\n";

// ── 5. Verify Region.php is actually readable ─────────────────────────────────
echo "=== VERIFY REGION.PHP ===\n";
$regionFile = $projectRoot . '/vendor/seatsio/seatsio-php/src/Region.php';
if (file_exists($regionFile)) {
    echo "✅ Region.php exists\n";
    $content = file_get_contents($regionFile);
    echo "First 150 chars: " . htmlspecialchars(substr($content, 0, 150)) . "\n";
} else {
    echo "❌ Region.php NOT found\n";
}
echo "\n";

// ── 6. Try manually requiring and instantiating ───────────────────────────────
echo "=== MANUAL REQUIRE TEST ===\n";
try {
    require_once $regionFile;
    $region = \Seatsio\Region::NA();
    echo "✅ Seatsio\\Region::NA() works! URL: " . $region->url() . "\n";
} catch (\Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// ── 7. Final verify with fresh include ───────────────────────────────────────
echo "=== FINAL PSR-4 MAP VERIFY ===\n";
// Re-read the (now potentially modified) file
$psr4New = include $psr4File;
$seatsioEntries = array_filter(array_keys($psr4New), fn($k) => str_contains($k, 'Seatsio'));
foreach ($seatsioEntries as $ns) {
    echo "✅ '$ns' => " . implode(', ', $psr4New[$ns]) . "\n";
}
if (empty($seatsioEntries)) {
    echo "❌ Still not in PSR-4 map\n";
}

echo "\n=== DONE ===\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo "Then visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";
echo '</pre>';
