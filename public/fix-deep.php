<?php
/**
 * fix-deep.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-deep.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$projectRoot = dirname(__DIR__);

// ── 1. Load autoloader and intercept what happens ────────────────────────────
echo "=== AUTOLOADER INTERNALS ===\n";
require $projectRoot . '/vendor/autoload.php';

// Get the registered autoloaders
$loaders = spl_autoload_functions();
echo "Registered autoload functions: " . count($loaders) . "\n";
foreach ($loaders as $i => $loader) {
    if (is_array($loader)) {
        $class = is_object($loader[0]) ? get_class($loader[0]) : $loader[0];
        echo "  [$i] $class::" . $loader[1] . "\n";
    } else {
        echo "  [$i] " . (is_string($loader) ? $loader : 'closure') . "\n";
    }
}
echo "\n";

// ── 2. Find the Composer ClassLoader instance ─────────────────────────────────
echo "=== CLASSLOADER PSR-4 PREFIXES ===\n";
$classLoader = null;
foreach ($loaders as $loader) {
    if (is_array($loader) && is_object($loader[0]) && $loader[0] instanceof \Composer\Autoload\ClassLoader) {
        $classLoader = $loader[0];
        break;
    }
}

if ($classLoader) {
    $prefixes = $classLoader->getPrefixesPsr4();
    $seatsio = array_filter(array_keys($prefixes), fn($k) => str_contains($k, 'Seatsio'));
    if ($seatsio) {
        foreach ($seatsio as $ns) {
            echo "✅ '$ns' => " . implode(', ', $prefixes[$ns]) . "\n";
        }
    } else {
        echo "❌ Seatsio NOT in ClassLoader PSR-4 prefixes\n";
        echo "Total PSR-4 prefixes registered: " . count($prefixes) . "\n";

        // Show first 5 to confirm loader is working
        $i = 0;
        foreach ($prefixes as $ns => $paths) {
            echo "   $ns\n";
            if (++$i >= 5) { echo "   ...\n"; break; }
        }
    }
} else {
    echo "❌ Could not find Composer ClassLoader instance\n";
}
echo "\n";

// ── 3. Manually trigger the autoloader for Seatsio\Region ────────────────────
echo "=== MANUAL AUTOLOAD TRIGGER ===\n";
if ($classLoader) {
    $file = $classLoader->findFile('Seatsio\\Region');
    echo "ClassLoader->findFile('Seatsio\\\\Region'): " . ($file ?: "❌ NOT FOUND") . "\n\n";
}

// ── 4. Check the actual autoload_psr4.php content around Seatsio ─────────────
echo "=== RAW PSR-4 FILE CONTENT (Seatsio lines) ===\n";
$psr4Content = file_get_contents($projectRoot . '/vendor/composer/autoload_psr4.php');
$lines = explode("\n", $psr4Content);
foreach ($lines as $line) {
    if (str_contains($line, 'eatsio') || str_contains($line, 'return') || str_contains($line, '$baseDir') && str_contains($line, 'vendor')) {
        echo htmlspecialchars($line) . "\n";
    }
}
echo "\n";

// ── 5. Check if $baseDir resolves correctly in that file ─────────────────────
echo "=== BASEDIR RESOLUTION ===\n";
// The autoload_psr4.php uses $baseDir — let's see what it resolves to
$psr4 = require $projectRoot . '/vendor/composer/autoload_psr4.php';
foreach ($psr4 as $ns => $paths) {
    if (str_contains($ns, 'Seatsio')) {
        echo "Namespace: $ns\n";
        foreach ($paths as $path) {
            echo "Path: $path\n";
            echo "Dir exists: " . (is_dir($path) ? "✅ YES" : "❌ NO") . "\n";
            echo "Region.php at path: " . (file_exists($path . '/Region.php') ? "✅ YES" : "❌ NO") . "\n";
        }
    }
}
echo "\n";

// ── 6. Force-register Seatsio and test ───────────────────────────────────────
echo "=== FORCE REGISTER AND TEST ===\n";
if ($classLoader) {
    $classLoader->addPsr4('Seatsio\\', $projectRoot . '/vendor/seatsio/seatsio-php/src/');
    echo "Manually added Seatsio\\ to ClassLoader\n";
    echo "Seatsio\\Region now: " . (class_exists('Seatsio\\Region') ? "✅ found" : "❌ still not found") . "\n";

    if (class_exists('Seatsio\\Region')) {
        $r = \Seatsio\Region::NA();
        echo "Region::NA() = " . $r->url() . "\n";
    }
} else {
    echo "No ClassLoader to register with\n";
}

echo "\n=== DONE ===\n";
echo "⚠️  DELETE THIS FILE: " . __FILE__ . "\n";
echo '</pre>';
