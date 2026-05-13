<?php
/**
 * fix-autoloader.php
 * Upload to public_html/public/ and visit:
 * https://3sixtyshows.alsabeelwater.com/fix-autoloader.php?secret=fix-2026
 * DELETE AFTER USE.
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'fix-2026') {
    die('Unauthorized. Add ?secret=fix-2026');
}

echo '<pre style="font-family:monospace;font-size:13px;background:#111;color:#0f0;padding:20px">';

$publicDir   = __DIR__;
$projectRoot = dirname($publicDir);
$composerPhar = $projectRoot . '/composer.phar';

echo "Project root: $projectRoot\n\n";

// ── 1. Fix .env — remove inline comment from SEATSIO_REGION ──────────────────
echo "=== FIX .ENV ===\n";
$envFile = $projectRoot . '/.env';
$envContent = file_get_contents($envFile);

// Replace the region line — strip everything after the value
$fixed = preg_replace(
    '/^(SEATSIO_REGION\s*=\s*)(.+?)(\s*#.*)$/m',
    '$1na',
    $envContent
);

if ($fixed !== $envContent) {
    file_put_contents($envFile, $fixed);
    echo "✅ Fixed SEATSIO_REGION line — removed inline comment\n\n";
} else {
    echo "ℹ️  SEATSIO_REGION line looks clean already\n\n";
}

// Also fix SEATSIO_SECRET_KEY and SEATSIO_PUBLIC_KEY if they have inline comments
$fixed2 = preg_replace(
    '/^(SEATSIO_SECRET_KEY\s*=\s*)([^\s#]+)(\s*#.*)$/m',
    '$1$2',
    $fixed
);
$fixed3 = preg_replace(
    '/^(SEATSIO_PUBLIC_KEY\s*=\s*)([^\s#]+)(\s*#.*)$/m',
    '$1$2',
    $fixed2
);
if ($fixed3 !== $fixed) {
    file_put_contents($envFile, $fixed3);
    echo "✅ Also cleaned up other inline comments\n\n";
}

// ── 2. Regenerate autoloader using composer.phar ──────────────────────────────
echo "=== REGENERATE AUTOLOADER ===\n";

if (!file_exists($composerPhar)) {
    echo "Downloading composer.phar...\n";
    $composerContent = file_get_contents('https://getcomposer.org/composer-stable.phar');
    if ($composerContent) {
        file_put_contents($composerPhar, $composerContent);
        echo "✅ Downloaded composer.phar\n";
    } else {
        echo "❌ Failed to download composer.phar\n";
    }
}

if (file_exists($composerPhar)) {
    echo "Running: php composer.phar dump-autoload --optimize\n";
    $output = [];
    $return = 0;
    exec(
        'cd ' . escapeshellarg($projectRoot) . ' && php ' . escapeshellarg($composerPhar) . ' dump-autoload --optimize --no-interaction 2>&1',
        $output,
        $return
    );
    foreach ($output as $line) {
        echo htmlspecialchars($line) . "\n";
    }
    echo ($return === 0 ? "✅ Autoloader regenerated\n" : "❌ Exit code: $return\n");
} else {
    echo "❌ composer.phar not available\n";
}

echo "\n";

// ── 3. Clear Laravel config cache ────────────────────────────────────────────
echo "=== CLEAR CONFIG CACHE ===\n";

// Delete cached config file directly (faster than artisan on shared hosting)
$cachedConfig = $projectRoot . '/bootstrap/cache/config.php';
$cachedRoutes = $projectRoot . '/bootstrap/cache/routes-v7.php';
$cachedServices = $projectRoot . '/bootstrap/cache/services.php';
$cachedPackages = $projectRoot . '/bootstrap/cache/packages.php';

foreach ([$cachedConfig, $cachedRoutes, $cachedServices, $cachedPackages] as $cacheFile) {
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
        $short = basename($cacheFile);
        echo "✅ Deleted $short\n";
    }
}

// Also try artisan
$output = [];
exec('cd ' . escapeshellarg($projectRoot) . ' && php artisan config:clear 2>&1', $output);
echo implode("\n", $output) . "\n";

echo "\n";

// ── 4. Verify fix ─────────────────────────────────────────────────────────────
echo "=== VERIFY ===\n";
require_once $projectRoot . '/vendor/autoload.php';
echo "Seatsio\\Region exists    : " . (class_exists('Seatsio\\Region')        ? "✅ YES" : "❌ NO") . "\n";
echo "Seatsio\\SeatsioClient exists: " . (class_exists('Seatsio\\SeatsioClient') ? "✅ YES" : "❌ NO") . "\n";

// Verify region value from env
$envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($envLines as $line) {
    if (str_starts_with($line, 'SEATSIO_REGION=')) {
        $val = trim(explode('=', $line, 2)[1]);
        echo "SEATSIO_REGION value    : '" . htmlspecialchars($val) . "'" .
             (trim($val) === 'na' || trim($val) === 'eu' ? " ✅" : " ❌ (should be 'na' or 'eu')") . "\n";
    }
}

echo "\n=== DONE ===\n";
echo "⚠️  DELETE THIS FILE NOW: " . __FILE__ . "\n";
echo "Then visit: https://3sixtyshows.alsabeelwater.com/shows/98/book\n";

echo '</pre>';
