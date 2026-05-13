<?php
if (!isset($_GET['secret']) || $_GET['secret'] !== 'patch2-2026') die('Unauthorized');

$root = dirname(__DIR__);
$file = $root . '/resources/views/booking/reserved.blade.php';
$content = file_get_contents($file);

// Fix 1: Add jQuery before Bootstrap in early_styles push
$oldBootstrap = "@push('early_styles')
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\">
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\" defer></script>
@endpush";

$newBootstrap = "@push('early_styles')
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\">
    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\"></script>
@endpush";

if (strpos($content, $oldBootstrap) !== false) {
    $content = str_replace($oldBootstrap, $newBootstrap, $content);
    echo "✅ Added jQuery before Bootstrap<br>";
} else {
    // Flexible fix - just add jQuery before any bootstrap.bundle line
    $content = preg_replace(
        '/(<script src="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap[^"]+bundle[^"]+"><\/script>)/',
        '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>' . "\n    " . '$1',
        $content
    );
    // Remove defer from bootstrap JS since jQuery must load first synchronously
    $content = str_replace('bootstrap.bundle.min.js" defer></script>', 'bootstrap.bundle.min.js"></script>', $content);
    echo "✅ Added jQuery via regex<br>";
}

// Fix 2: Replace window.SeatsioSeatingChart with seatsio.SeatingChart
// The new Seats.io SDK uses seatsio.SeatingChart not window.SeatsioSeatingChart
$content = str_replace(
    'new window.SeatsioSeatingChart(',
    'new seatsio.SeatingChart(',
    $content
);
echo "✅ Fixed SeatsioSeatingChart constructor<br>";

file_put_contents($file, $content);

// Clear all caches
$cleared = 0;
foreach (glob($root . '/storage/framework/views/*.php') as $f) { unlink($f); $cleared++; }
foreach (glob($root . '/bootstrap/cache/*.php') as $f) { unlink($f); $cleared++; }
if (function_exists('opcache_reset')) opcache_reset();
echo "✅ Cleared $cleared cache files<br>";

// Verify changes
$content = file_get_contents($file);
$lines = explode("\n", $content);
echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:12px'>";
echo "=== PUSH BLOCKS (lines 1-12) ===\n";
foreach (array_slice($lines, 0, 12) as $i => $line) {
    echo ($i+1) . ": " . htmlspecialchars($line) . "\n";
}
echo "\n=== SeatsioSeatingChart line ===\n";
foreach ($lines as $i => $line) {
    if (stripos($line, 'SeatingChart') !== false || stripos($line, 'SeatsioSeating') !== false) {
        echo ($i+1) . ": " . htmlspecialchars($line) . "\n";
    }
}
echo "</pre>";
echo "<a href='https://3sixtyshows.alsabeelwater.com/shows/98/book'>→ Visit booking page</a>";
