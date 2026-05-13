<?php
if (!isset($_GET['secret']) || $_GET['secret'] !== 'patch3-2026') die('Unauthorized');

$root = dirname(__DIR__);
$file = $root . '/resources/views/booking/reserved.blade.php';
$content = file_get_contents($file);

// Fix 1: publicKey -> workspaceKey
$content = str_replace(
    'publicKey:  PUBLIC_KEY,',
    'workspaceKey: PUBLIC_KEY,',
    $content
);
echo "✅ Fixed publicKey → workspaceKey<br>";

// Fix 2: tooltipInfo -> popoverInfo
$content = str_replace('tooltipInfo:', 'popoverInfo:', $content);
echo "✅ Fixed tooltipInfo → popoverInfo<br>";

file_put_contents($file, $content);

// Clear caches
$cleared = 0;
foreach (glob($root . '/storage/framework/views/*.php') as $f) { unlink($f); $cleared++; }
if (function_exists('opcache_reset')) opcache_reset();
echo "✅ Cleared $cleared cached views<br><br>";

// Verify
$content = file_get_contents($file);
$lines = explode("\n", $content);
echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:12px'>";
foreach ($lines as $i => $line) {
    if (stripos($line, 'workspaceKey') !== false ||
        stripos($line, 'publicKey') !== false ||
        stripos($line, 'popoverInfo') !== false ||
        stripos($line, 'tooltipInfo') !== false) {
        echo ($i+1) . ": " . htmlspecialchars($line) . "\n";
    }
}
echo "</pre>";

// Also show current event key from DB
echo "<br><strong>Now fix the event key 404:</strong><br>";
echo "The event key in DB: c52c527a-da63-46c4-8d41-345cc98030eb<br>";
echo "Check in your Seats.io dashboard that this event exists and belongs to workspace: abfbc646-69b5-4d7a-8214-9952a179d476<br><br>";
echo "<a href='https://3sixtyshows.alsabeelwater.com/shows/98/book'>→ Visit booking page</a>";
