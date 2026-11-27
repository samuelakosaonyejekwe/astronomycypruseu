<?php
/* ==========================================================================
   OLD ADDRESSES → NEW ADDRESSES
   The original site used addresses like /about-us/history.html and
   /pages/historyen.htm. Visitors and search engines using those are sent
   (301, permanent) to the new page. Extra mappings: data/redirects.php
   ========================================================================== */

/** Return the new URL for an old request path, or null. */
function legacy_redirect_target($uri_path)
{
    $path = rawurldecode((string)$uri_path);
    if (strlen($path) > 300 || strpos($path, "\0") !== false) return null;
    $key = trim($path, '/');
    $key = preg_replace('/\.(html?|php)$/i', '', $key);
    if ($key === 'index' || $key === '') return '/';

    $map = require SITE_ROOT . '/data/redirects.php';
    if (array_key_exists($key, $map)) {
        $to = $map[$key];
        if ($to === null) return null;
        if (preg_match('#^https://#', $to)) return $to;
        $key = $to;
    }
    if (preg_match('#^(en|el|ru)/#', $key)) return null;   // already a new address
    $pages = require SITE_ROOT . '/data/pages.php';
    if (isset($pages[$key])) return $key === '' ? '/en/' : '/en/' . $key . '.php';
    return null;
}
