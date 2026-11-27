<?php
/* ==========================================================================
   BOOTSTRAP  –  loaded at the top of every page (no need to edit)
   Every page sets $page = [...] and then requires this file.
   ========================================================================== */

if (!isset($page) || !is_array($page)) $page = [];

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

/* Current language: from the page itself, validated against the allowed list */
$lang = (isset($page['lang']) && isset($LANGUAGES[$page['lang']])) ? $page['lang'] : DEFAULT_LANG;
define('CURRENT_LANG', $lang);

/* UI text: English is always loaded as fallback */
require __DIR__ . '/lang/en.php';
$STR_EN = $STR;
$NAV_LABELS_EN = $NAV_LABELS;
if ($lang !== 'en') {
    require __DIR__ . '/lang/' . $lang . '.php';
    $NAV_LABELS = $NAV_LABELS + $NAV_LABELS_EN;
}

/* Page registry (titles, parents, images) used by menus, breadcrumbs, search */
$PAGES = require SITE_ROOT . '/data/pages.php';

/* Defaults for this page */
$page += [
    'path'        => '',
    'title'       => SITE_NAME,
    'description' => SITE_NAME,
    'image'       => '/images/index-page/earthrise.jpg',
    'translated'  => true,
    'children'    => true,
];

/* Remember the visitor's language for the site entrance (/) */
if (!headers_sent() && (!isset($_COOKIE['kpo_lang']) || $_COOKIE['kpo_lang'] !== $lang)) {
    setcookie('kpo_lang', $lang, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax', 'httponly' => true]);
}

/* Security headers (also set in .htaccess; repeated here for servers without it) */
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=(), payment=()');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; media-src 'self' https://astronomycyprus.eu; script-src 'self'; style-src 'self'; frame-src https://www.google.com https://www.youtube.com https://www.youtube-nocookie.com; connect-src 'self'; base-uri 'self'; form-action 'self'; object-src 'none'; frame-ancestors 'self'");
}
