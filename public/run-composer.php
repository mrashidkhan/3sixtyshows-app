<?php
/**
 * run-composer.php
 *
 * Upload this file to your PUBLIC root (public_html or public folder),
 * then visit: https://3sixtyshows.alsabeelwater.com/run-composer.php
 *
 * DELETE THIS FILE IMMEDIATELY after use — it is a security risk.
 */

// ── Basic security — change this password before uploading ──────────────────
define('SECRET', 'delete-me-after-use-2026');

if (!isset($_GET['secret']) || $_GET['secret'] !== SECRET) {
    die('Unauthorized. Access via: /run-composer.php?secret=' . SECRET);
}

// ── Configuration ────────────────────────────────────────────────────────────
// Adjust this path to your Laravel project root (one level above public_html)
$projectRoot = dirname(__DIR__); // goes up one level from public_html

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';
echo "Project root: $projectRoot\n\n";

// ── Helper ───────────────────────────────────────────────────────────────────
function run(string $cmd, string $cwd): void
{
    echo "▶ $cmd\n";
    $output = [];
    $return = 0;
    exec("cd " . escapeshellarg($cwd) . " && $cmd 2>&1", $output, $return);
    foreach ($output as $line) {
        echo htmlspecialchars($line) . "\n";
    }
    echo ($return === 0 ? "✅ Done\n" : "❌ Exit code: $return\n") . "\n";
}

// ── Step 1: Check PHP version ─────────────────────────────────────────────
echo "=== PHP VERSION ===\n";
echo PHP_VERSION . "\n\n";

// ── Step 2: Find composer ────────────────────────────────────────────────
echo "=== FINDING COMPOSER ===\n";
$composerPaths = [
    'composer',
    '/usr/local/bin/composer',
    '/usr/bin/composer',
    $projectRoot . '/composer.phar',
    dirname(__DIR__) . '/composer.phar',
];

$composer = null;
foreach ($composerPaths as $path) {
    $test = shell_exec("which $path 2>/dev/null || ls $path 2>/dev/null");
    if ($test) {
        $composer = $path;
        echo "Found composer at: $path\n\n";
        break;
    }
}

if (!$composer) {
    echo "Composer not found in standard locations. Downloading composer.phar...\n";
    $composerPhar = $projectRoot . '/composer.phar';
    copy('https://getcomposer.org/composer-stable.phar', $composerPhar);
    if (file_exists($composerPhar)) {
        $composer = 'php ' . escapeshellarg($composerPhar);
        echo "Downloaded composer.phar to $composerPhar\n\n";
    } else {
        die("❌ Could not find or download Composer. Please install it manually.\n</pre>");
    }
}

// ── Step 3: composer install ─────────────────────────────────────────────
echo "=== COMPOSER INSTALL ===\n";
run("$composer install --no-dev --optimize-autoloader --no-interaction", $projectRoot);

// ── Step 4: Verify seatsio package installed ─────────────────────────────
echo "=== VERIFY SEATSIO PACKAGE ===\n";
$seatsioPath = $projectRoot . '/vendor/seatsio/seatsio-php/src/Region.php';
if (file_exists($seatsioPath)) {
    echo "✅ seatsio/seatsio-php is installed correctly\n\n";
} else {
    echo "❌ seatsio/seatsio-php NOT found at: $seatsioPath\n\n";
}

// ── Step 5: Clear Laravel caches ─────────────────────────────────────────
echo "=== CLEARING LARAVEL CACHES ===\n";
run("php artisan config:clear", $projectRoot);
run("php artisan cache:clear", $projectRoot);
run("php artisan view:clear", $projectRoot);
run("php artisan route:clear", $projectRoot);

// ── Step 6: Optimize ─────────────────────────────────────────────────────
echo "=== OPTIMIZING ===\n";
run("php artisan config:cache", $projectRoot);
run("php artisan route:cache", $projectRoot);

echo "=== ALL DONE ===\n";
echo "⚠️  DELETE THIS FILE NOW: " . __FILE__ . "\n";
echo "Visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";

echo '</pre>';
