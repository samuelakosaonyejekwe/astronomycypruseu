<?php
/* ==========================================================================
   ROUTER FOR THE PHP BUILT-IN SERVER (local preview only)
     PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php   (Windows: without the first part)
   On Apache hosting, .htaccess does the same job and this file is not used.
   ========================================================================== */
define('SITE_ROOT', __DIR__);
$uri  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$path = rawurldecode(parse_url($uri, PHP_URL_PATH));

/* Never serve internal files */
if (preg_match('#^/(includes|data/[^/]+\.php|tools/(?!rebuild-index\.php)|\.ht|README|DREAMWEAVER|HANDOVER|[^/]+\.md$|router\.php)#i', $path)
    || preg_match('#/\.#', $path)) {
    http_response_code(403);
    exit('Forbidden');
}

$file = realpath(__DIR__ . $path);
$inside = $file !== false && strpos($file, __DIR__) === 0;

if ($inside && is_dir($file)) {
    if (substr($path, -1) !== '/') { header('Location: ' . $path . '/', true, 301); exit; }
    if (is_file($file . '/index.php')) { $_SERVER['SCRIPT_NAME'] = rtrim($path, '/') . '/index.php'; chdir($file); require $file . '/index.php'; exit; }
}
if ($inside && is_file($file)) {
    if (preg_match('/\.(jpe?g|png|gif|webp|svg|css|js|json|pdf|mp4|ico)$/i', $file)) {
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: public, max-age=86400');
    }
    return false;   // let the built-in server send the file / run the PHP
}
require __DIR__ . '/404.php';
