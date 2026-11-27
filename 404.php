<?php
/* ==========================================================================
   NOT FOUND HANDLER (used by .htaccess and router.php)
   First tries to send old addresses to their new page, otherwise shows the
   "page not found" page in the visitor's language.
   ========================================================================== */
if (!defined('SITE_ROOT')) define('SITE_ROOT', __DIR__);
require_once SITE_ROOT . '/includes/redirects.php';

$req = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
$to = legacy_redirect_target($req);
if ($to !== null) {
    header('Location: ' . $to, true, 301);
    exit;
}
$lang = 'en';
if (preg_match('#^/(en|el|ru)/#', (string)$req, $m)) $lang = $m[1];
require SITE_ROOT . '/' . $lang . '/404.php';
