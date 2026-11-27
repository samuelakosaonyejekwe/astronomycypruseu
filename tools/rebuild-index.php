<?php
/* ==========================================================================
   REBUILD THE PAGE LIST AND SEARCH INDEX
   Run this after adding, renaming or editing pages:
     - command line:  php tools/rebuild-index.php
     - or in a browser on your own computer: http://localhost:8000/tools/rebuild-index.php
   It reads every page in /en, /el and /ru and writes:
     data/pages.php            (titles, parent pages, images – used by menus/breadcrumbs)
     data/search-<lang>.json   (the search index)
   ========================================================================== */

$is_cli = (PHP_SAPI === 'cli');
if (!$is_cli) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
        http_response_code(403);
        exit('Forbidden: run this tool from the command line or from localhost.');
    }
    header('Content-Type: text/plain; charset=UTF-8');
}

define('SITE_ROOT', dirname(__DIR__));
require SITE_ROOT . '/includes/config.php';

/* Pages whose natural parent is not their folder */
$PARENT_OVERRIDES = [
    'program-events/public-events/'          => 'program-events/monthly-program',
    'planetarium/cyprus-geology/'            => 'planetarium/exhibits',
    'pages/partial-solar-eclipse'            => 'program-events/astronomical-phenomena',
    'pages/legal-notices'                    => '',
    'projects/international-projects/archive/' => 'projects/international-projects',
];
$SKIP = ['search', 'sitemap', '404'];
$MONTHS = ['january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6,
    'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12];

function grab($src, $key)
{
    if (preg_match("/'" . $key . "'\s*=>\s*'((?:[^'\\\\]|\\\\.)*)'/u", $src, $m)) {
        return str_replace(['\\\\', "\\'"], ['\\', "'"], $m[1]);
    }
    return null;
}
function clean_text($html)
{
    $t = preg_replace('/<(script|style)\b.*?<\/\1>/is', ' ', $html);
    $t = preg_replace('/<[^>]+>/', ' ', $t);
    $t = html_entity_decode($t, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $t = preg_replace('/\s+/u', ' ', $t);
    return trim($t);
}
function cut($s, $n)
{
    if (preg_match('/^.{0,' . $n . '}/us', $s, $m)) return $m[0];
    return $s;
}

$found = [];   // path => ['title'=>[lang=>..], 'img'=>.., 'text'=>[lang=>..]]
foreach (array_keys($LANGUAGES) as $lang) {
    $dir = SITE_ROOT . '/' . $lang;
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->getExtension() !== 'php') continue;
        $src = file_get_contents($f->getPathname());
        $path = grab($src, 'path');
        if ($path === null) continue;
        if (in_array($path, $SKIP, true)) continue;
        $title = grab($src, 'title');
        $body = $src;
        if (preg_match('/CONTENT START.*?-->(.*)<!--[ =]*CONTENT END/s', $src, $m)) $body = $m[1];
        $found[$path]['title'][$lang] = $title !== null ? $title : $path;
        $found[$path]['text'][$lang] = clean_text($body);
        $found[$path]['translated'][$lang] = !preg_match("/'translated'\s*=>\s*false/", $src);
        if (empty($found[$path]['img']) && preg_match_all('/<img[^>]+src="(\/images\/[^"]+)"/i', $body, $mm)) {
            foreach ($mm[1] as $im) {
                if (!preg_match('/(logo|tripadvisor|button|arrow|icon|flag|gift-shop|visit-our)/i', $im)) { $found[$path]['img'] = $im; break; }
            }
        }
    }
}

/* parents, groups, sort keys */
$PAGES = [];
ksort($found);
foreach ($found as $path => $info) {
    $parent = null;
    if ($path !== '') {
        foreach ($PARENT_OVERRIDES as $prefix => $par) {
            if (strpos($path, $prefix) === 0) { $parent = $par; break; }
        }
        if ($parent === null) {
            $segs = explode('/', $path);
            array_pop($segs);
            while ($segs) {
                $cand = implode('/', $segs);
                if (isset($found[$cand])) { $parent = $cand; break; }
                array_pop($segs);
            }
            if ($parent === null) $parent = '';
        }
    }
    $slug = basename($path);
    $group = '';
    if (preg_match('/(?:^|[-_])((?:19|20)\d\d)(?:$|[-_+])/', $slug, $m)) $group = $m[1];
    elseif (preg_match('#/((?:19|20)\d\d)/#', '/' . $path, $m)) $group = $m[1];
    $sort = $group;
    if (preg_match('/(\d{1,2})-(' . implode('|', array_keys($MONTHS)) . ')-((?:19|20)\d\d)/i', $slug, $m)) {
        $sort = sprintf('%s-%02d-%02d', $m[3], $MONTHS[strtolower($m[2])], $m[1]);
    } elseif (preg_match('/(' . implode('|', array_keys($MONTHS)) . ')-((?:19|20)\d\d)/i', $path, $m)) {
        $sort = sprintf('%s-%02d', $m[2], $MONTHS[strtolower($m[1])]);
    }
    $PAGES[$path] = [
        'parent' => $parent,
        'title'  => $info['title'],
        'img'    => isset($info['img']) ? $info['img'] : '',
        'group'  => $group,
        'sort'   => $sort . ' ' . $slug,
    ];
}

$out = "<?php\n/* GENERATED by tools/rebuild-index.php – do not edit by hand, run the tool instead. */\nreturn " . var_export($PAGES, true) . ";\n";
file_put_contents(SITE_ROOT . '/data/pages.php', $out);

/* search index per language */
foreach (array_keys($LANGUAGES) as $lang) {
    $idx = [];
    foreach ($found as $path => $info) {
        if (!isset($info['text'][$lang])) continue;
        $crumbs = [];
        $p = $PAGES[$path]['parent'];
        $guard = 0;
        while ($p !== null && $p !== '' && $guard++ < 8) {
            $crumbs[] = isset($PAGES[$p]['title'][$lang]) ? $PAGES[$p]['title'][$lang] : $p;
            $p = isset($PAGES[$p]['parent']) ? $PAGES[$p]['parent'] : null;
        }
        $idx[] = [
            'u' => $path === '' ? '/' . $lang . '/' : '/' . $lang . '/' . $path . '.php',
            't' => $info['title'][$lang],
            'c' => implode(' › ', array_reverse($crumbs)),
            'x' => cut($info['text'][$lang], 5000),
        ];
    }
    file_put_contents(SITE_ROOT . '/data/search-' . $lang . '.json', json_encode($idx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

/* sitemap.xml (all pages, all languages, with language alternates) and robots.txt */
$x = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
foreach ($PAGES as $path => $info) {
    foreach (array_keys($LANGUAGES) as $lang) {
        if (!isset($info['title'][$lang])) continue;
        $u = rtrim(SITE_URL, '/') . '/' . $lang . '/' . ($path === '' ? '' : $path . '.php');
        $x .= '  <url><loc>' . htmlspecialchars($u, ENT_XML1) . '</loc>';
        foreach (array_keys($LANGUAGES) as $alt) {
            if (!isset($info['title'][$alt])) continue;
            $au = rtrim(SITE_URL, '/') . '/' . $alt . '/' . ($path === '' ? '' : $path . '.php');
            $x .= '<xhtml:link rel="alternate" hreflang="' . $LANGUAGES[$alt][2] . '" href="' . htmlspecialchars($au, ENT_XML1) . '"/>';
        }
        $x .= "</url>\n";
    }
}
$x .= "</urlset>\n";
file_put_contents(SITE_ROOT . '/sitemap.xml', $x);
file_put_contents(SITE_ROOT . '/robots.txt', "User-agent: *\nDisallow: /includes/\nDisallow: /tools/\nDisallow: /data/\nDisallow: /*/search.php\n\nSitemap: " . rtrim(SITE_URL, '/') . "/sitemap.xml\n");

echo 'Indexed ' . count($PAGES) . " pages.\n";
