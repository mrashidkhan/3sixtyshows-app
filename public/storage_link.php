<?php
// storage_link.php
// Place this file in your Laravel /public folder
// Access via: https://yourdomain.com/storage_link.php
// DELETE this file immediately after running it!

$target = dirname(__DIR__) . '/storage/app/public';
$link   = __DIR__ . '/storage';

if (file_exists($link) || is_link($link)) {
    echo '⚠️ Symlink or folder already exists at: ' . $link . '<br>';
    echo 'If it is wrong, delete it manually via FTP and run again.';
    exit;
}

if (symlink($target, $link)) {
    echo '✅ Storage symlink created successfully!<br>';
    echo 'Target : ' . $target . '<br>';
    echo 'Link   : ' . $link . '<br>';
    echo '<strong>⚠️ Please delete this file (storage_link.php) now for security!</strong>';
} else {
    echo '❌ Failed to create symlink. Your host may not allow symlink() via PHP.';
    echo '<br>Try the artisan fallback below.';
}
