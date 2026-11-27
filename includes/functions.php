<?php
/* ==========================================================================
   HELPER FUNCTIONS  (no need to edit)
   ========================================================================== */

/** Escape text for safe HTML output. */
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Translate a UI string key for the current language (falls back to English). */
function t($key)
{
    global $STR, $STR_EN;
    if (isset($STR[$key])) return $STR[$key];
    if (isset($STR_EN[$key])) return $STR_EN[$key];
    return $key;
}

/** URL of a page in a language. $path is e.g. 'about-us/history' ('' = home). */
function url($path, $lang = null)
{
    if ($lang === null) $lang = CURRENT_LANG;
    $path = trim((string)$path, '/');
    if ($path === '') return '/' . $lang . '/';
    $hash = '';
    if (($p = strpos($path, '#')) !== false) {
        $hash = substr($path, $p);
        $path = substr($path, 0, $p);
    }
    return '/' . $lang . '/' . $path . '.php' . $hash;
}

/** Title of a registered page in the current language. */
function page_title($path, $lang = null)
{
    global $PAGES, $NAV_LABELS;
    if ($lang === null) $lang = CURRENT_LANG;
    if ($lang === CURRENT_LANG && isset($NAV_LABELS[$path])) return $NAV_LABELS[$path];
    if (isset($PAGES[$path]['title'][$lang])) return $PAGES[$path]['title'][$lang];
    if (isset($PAGES[$path]['title']['en'])) return $PAGES[$path]['title']['en'];
    return $path;
}

/** Does a page file exist for this language? */
function page_exists($path, $lang)
{
    $file = SITE_ROOT . '/' . $lang . '/' . ($path === '' ? 'index' : $path) . '.php';
    return is_file($file);
}

/** Chain of parent pages for the breadcrumb, from the top down. */
function breadcrumb_chain($path)
{
    global $PAGES;
    $chain = [];
    $guard = 0;
    $p = isset($PAGES[$path]['parent']) ? $PAGES[$path]['parent'] : null;
    while ($p !== null && $p !== '' && $guard++ < 10) {
        array_unshift($chain, $p);
        $p = isset($PAGES[$p]['parent']) ? $PAGES[$p]['parent'] : null;
    }
    return $chain;
}

/** Child pages of a page, as [path => info], newest group first. */
function children_of($path)
{
    global $PAGES;
    $out = [];
    foreach ($PAGES as $p => $info) {
        if (isset($info['parent']) && $info['parent'] === $path) $out[$p] = $info;
    }
    uasort($out, function ($a, $b) {
        $ga = isset($a['group']) ? $a['group'] : '';
        $gb = isset($b['group']) ? $b['group'] : '';
        if ($ga !== $gb) return strcmp($gb, $ga);
        $sa = isset($a['sort']) ? $a['sort'] : '';
        $sb = isset($b['sort']) ? $b['sort'] : '';
        return strcmp($sb, $sa);
    });
    return $out;
}

/** Which menu group is a page in (for highlighting the menu). */
function menu_group_of($path)
{
    global $MENU;
    $check = $path;
    $chain = breadcrumb_chain($path);
    $candidates = array_merge([$check], array_reverse($chain));
    foreach ($candidates as $c) {
        foreach ($MENU as $i => $g) {
            if (isset($g['href']) && $g['href'] === $c && $c !== '') return $i;
            if (in_array($c, $g['items'], true)) return $i;
        }
    }
    return $path === '' ? 0 : -1;
}

/** Absolute address (for search engines and social media). */
function abs_url($path_or_url)
{
    if (preg_match('#^https?://#', $path_or_url)) return $path_or_url;
    return rtrim(SITE_URL, '/') . '/' . ltrim($path_or_url, '/');
}
