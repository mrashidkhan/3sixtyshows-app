<?php
if (!isset($_GET['secret']) || $_GET['secret'] !== 'clr-2026') die('Unauthorized');
$root = dirname(__DIR__);
$cleared = 0;
foreach (glob($root . '/storage/framework/views/*.php') as $f) {
    unlink($f);
    $cleared++;
}
foreach (glob($root . '/bootstrap/cache/*.php') as $f) {
    unlink($f);
    $cleared++;
}
if (function_exists('opcache_reset')) opcache_reset();
echo "✅ Cleared $cleared cached files. <a href='https://3sixtyshows.alsabeelwater.com/shows/98/book'>Visit booking page</a>";
