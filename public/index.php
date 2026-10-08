<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($path === '/' || $path === '/index.html') {
    readfile(__DIR__ . '/index.html');
    exit;
}

require __DIR__ . '/../src/routes/api.php';
