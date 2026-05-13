<?php
if (!isset($_GET['secret']) || $_GET['secret'] !== 'chk-2026') die('Unauthorized');
$root = dirname(__DIR__);
$file = $root . '/resources/views/booking/reserved.blade.php';
echo '<pre style="background:#111;color:#0f0;padding:20px;font-size:12px">';
echo "File exists: " . (file_exists($file) ? "YES" : "NO") . "\n";
echo "Last modified: " . date('Y-m-d H:i:s', filemtime($file)) . "\n\n";
echo "=== LINES 1-20 ===\n";
$lines = file($file);
foreach (array_slice($lines, 0, 20) as $i => $line) {
    echo ($i+1) . ": " . htmlspecialchars($line);
}
echo "\n=== SEARCHING FOR 'bootstrap' ===\n";
foreach ($lines as $i => $line) {
    if (stripos($line, 'bootstrap') !== false) {
        echo ($i+1) . ": " . htmlspecialchars($line);
    }
}
echo "\n=== SEARCHING FOR 'chart.js' ===\n";
foreach ($lines as $i => $line) {
    if (stripos($line, 'chart.js') !== false) {
        echo ($i+1) . ": " . htmlspecialchars($line);
    }
}

// Also clear all caches
$cleared = 0;
foreach (glob($root . '/storage/framework/views/*.php') as $f) { unlink($f); $cleared++; }
foreach (glob($root . '/bootstrap/cache/*.php') as $f) { unlink($f); $cleared++; }
if (function_exists('opcache_reset')) opcache_reset();
echo "\n✅ Also cleared $cleared cache files\n";
echo '</pre>';
