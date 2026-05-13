<?php
/**
 * fix-autoloader2.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-autoloader2.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$publicDir    = __DIR__;
$projectRoot  = dirname($publicDir);
$composerPhar = $projectRoot . '/composer.phar';
$tmpHome      = $projectRoot . '/storage/composer-tmp';

echo "Project root: $projectRoot\n\n";

// ── Create a writable HOME directory for composer ─────────────────────────────
if (!is_dir($tmpHome)) {
    mkdir($tmpHome, 0755, true);
}
echo "HOME dir: $tmpHome\n\n";

// ── Regenerate autoloader with HOME set ───────────────────────────────────────
echo "=== REGENERATE AUTOLOADER ===\n";

$cmd = 'HOME=' . escapeshellarg($tmpHome) .
       ' COMPOSER_HOME=' . escapeshellarg($tmpHome) .
       ' php ' . escapeshellarg($composerPhar) .
       ' dump-autoload --optimize --no-interaction 2>&1';

echo "Running: php composer.phar dump-autoload --optimize\n";

$output = [];
$return = 0;
exec('cd ' . escapeshellarg($projectRoot) . ' && ' . $cmd, $output, $return);

foreach ($output as $line) {
    echo htmlspecialchars($line) . "\n";
}
echo ($return === 0 ? "✅ Autoloader regenerated\n" : "❌ Exit code: $return\n");
echo "\n";

// ── Verify ────────────────────────────────────────────────────────────────────
echo "=== VERIFY ===\n";

// Load fresh autoloader
require_once $projectRoot . '/vendor/autoload.php';

echo "Seatsio\\Region exists      : " . (class_exists('Seatsio\\Region')        ? "✅ YES" : "❌ NO") . "\n";
echo "Seatsio\\SeatsioClient exists: " . (class_exists('Seatsio\\SeatsioClient') ? "✅ YES" : "❌ NO") . "\n\n";

// ── Check autoload classmap directly ──────────────────────────────────────────
echo "=== CLASSMAP CHECK ===\n";
$classmapFile = $projectRoot . '/vendor/composer/autoload_classmap.php';
if (file_exists($classmapFile)) {
    $classmap = require $classmapFile;
    $seatsioClasses = array_filter(array_keys($classmap), fn($k) => str_contains($k, 'Seatsio'));
    if (count($seatsioClasses) > 0) {
        echo "✅ Found " . count($seatsioClasses) . " Seatsio classes in classmap:\n";
        foreach (array_slice($seatsioClasses, 0, 5) as $class) {
            echo "   $class\n";
        }
        if (count($seatsioClasses) > 5) echo "   ...and " . (count($seatsioClasses) - 5) . " more\n";
    } else {
        echo "❌ No Seatsio classes in classmap\n";

        // Check if the PSR-4 autoload_psr4.php has it instead
        $psr4File = $projectRoot . '/vendor/composer/autoload_psr4.php';
        if (file_exists($psr4File)) {
            $psr4 = require $psr4File;
            $seatsioPsr4 = array_filter(array_keys($psr4), fn($k) => str_contains($k, 'Seatsio'));
            if (count($seatsioPsr4) > 0) {
                echo "✅ Found Seatsio in PSR-4 map:\n";
                foreach ($seatsioPsr4 as $ns) {
                    echo "   $ns => " . implode(', ', $psr4[$ns]) . "\n";
                }
            } else {
                echo "❌ Seatsio not in PSR-4 map either\n";
            }
        }
    }
} else {
    echo "❌ classmap file not found\n";
}

echo "\n=== DONE ===\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo "Then visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";
echo '</pre>';
