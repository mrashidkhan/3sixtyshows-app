<?php
if (!isset($_GET['secret']) || $_GET['secret'] !== 'patch-2026') die('Unauthorized');

$root = dirname(__DIR__);
$file = $root . '/resources/views/booking/reserved.blade.php';

$content = file_get_contents($file);

// Replace local asset paths with CDN URLs
$old = "@push('early_styles')
    <link rel=\"stylesheet\" href=\"{{ asset('assets/css/bootstrap.min.css') }}\">
    <script src=\"{{ asset('assets/js/bootstrap.bundle.min.js') }}\" defer></script>
@endpush";

$new = "@push('early_styles')
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\">
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\" defer></script>
@endpush";

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    file_put_contents($file, $content);
    echo "✅ Bootstrap paths updated to CDN<br>";
} else {
    // Try a more flexible replacement
    $content = preg_replace(
        "/asset\('assets\/css\/bootstrap\.min\.css'\)/",
        "'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css'",
        $content
    );
    $content = preg_replace(
        "/asset\('assets\/js\/bootstrap\.bundle\.min\.js'\)/",
        "'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js'",
        $content
    );
    file_put_contents($file, $content);
    echo "✅ Bootstrap paths updated via regex<br>";
}

// Clear view cache
$cleared = 0;
foreach (glob($root . '/storage/framework/views/*.php') as $f) { unlink($f); $cleared++; }
foreach (glob($root . '/bootstrap/cache/*.php') as $f) { unlink($f); $cleared++; }
if (function_exists('opcache_reset')) opcache_reset();
echo "✅ Cleared $cleared cache files<br>";

// Verify
$content = file_get_contents($file);
$lines = explode("\n", $content);
echo "<pre style='background:#111;color:#0f0;padding:20px'>";
echo "=== LINES 7-10 AFTER FIX ===\n";
foreach (array_slice($lines, 6, 4) as $i => $line) {
    echo ($i+7) . ": " . htmlspecialchars($line) . "\n";
}
echo "</pre>";
echo "<a href='https://3sixtyshows.alsabeelwater.com/shows/98/book'>Visit booking page</a>";
