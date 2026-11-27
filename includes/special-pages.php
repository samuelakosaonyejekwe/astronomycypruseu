<?php
/* ==========================================================================
   SPECIAL PAGES – search results, site map and "page not found".
   Each language folder has a small search.php / sitemap.php / 404.php that
   sets $page['special'] and includes this file.
   ========================================================================== */

$special = isset($page['special']) ? $page['special'] : '';

if ($special === 'search') {
    /* Input: ?q=  (limited length, never echoed unescaped) */
    $q = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
    if (strlen($q) > 80) $q = substr($q, 0, 80);
    $results = [];
    $norm = function ($s) {
        $s = function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s);
        if (class_exists('Normalizer')) $s = preg_replace('/\p{Mn}/u', '', Normalizer::normalize($s, Normalizer::FORM_D));
        return str_replace('ς', 'σ', $s);
    };
    if ($q !== '' && preg_match('//u', $q)) {
        $file = SITE_ROOT . '/data/search-' . CURRENT_LANG . '.json';
        $idx = is_file($file) ? json_decode(file_get_contents($file), true) : [];
        $terms = array_filter(preg_split('/\s+/u', $norm($q)), function ($w) { return strlen($w) > 1; });
        foreach ((array)$idx as $p) {
            $tt = $norm($p['t']);
            $xx = $norm($p['x']);
            $score = 0;
            foreach ($terms as $w) {
                $inT = strpos($tt, $w) !== false;
                $inX = strpos($xx, $w) !== false;
                if (!$inT && !$inX) { $score = 0; break; }
                $score += $inT ? 10 : 1;
            }
            if ($score > 0) { $p['score'] = $score; $results[] = $p; }
        }
        usort($results, function ($a, $b) { return $b['score'] - $a['score']; });
        $results = array_slice($results, 0, 100);
    }
    ?>
    <article class="content search-page" data-search-page data-index="/data/search-<?php echo e(CURRENT_LANG); ?>.json" data-empty="<?php echo e(t('no_results')); ?>" data-results-for="<?php echo e(t('results_for')); ?>">
      <form action="<?php echo e(url('search')); ?>" method="get" role="search">
        <label class="visually-hidden" for="q2"><?php echo e(t('search')); ?></label>
        <input id="q2" type="search" name="q" maxlength="80" value="<?php echo e($q); ?>" placeholder="<?php echo e(t('search_placeholder')); ?>">
        <button class="btn" type="submit"><?php echo e(t('search')); ?></button>
      </form>
      <?php if ($q !== ''): ?>
        <p><strong><?php echo count($results); ?></strong> <?php echo e(t('results_for')); ?> “<?php echo e($q); ?>”</p>
        <?php if (!$results): ?><p><?php echo e(t('no_results')); ?></p><?php endif; ?>
        <ol>
          <?php foreach ($results as $r): ?>
          <li>
            <?php if ($r['c']): ?><small><?php echo e($r['c']); ?></small><br><?php endif; ?>
            <a href="<?php echo e($r['u']); ?>"><strong><?php echo e($r['t']); ?></strong></a><br>
            <span><?php echo e(preg_match('/^.{0,220}/us', $r['x'], $mm) ? $mm[0] . '…' : ''); ?></span>
          </li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
    </article>
    <?php
} elseif ($special === 'sitemap') {
    ?>
    <article class="content">
      <p><?php echo e(t('sitemap_intro')); ?></p>
      <div class="sitemap-cols">
        <?php
        $listed = [];
        $tree = function ($path, $depth) use (&$tree, &$listed) {
            $kids = children_of($path);
            if (!$kids || $depth > 4) return;
            echo '<ul>';
            foreach ($kids as $kp => $ki) {
                if (isset($listed[$kp])) continue;
                $listed[$kp] = true;
                echo '<li><a href="' . e(url($kp)) . '">' . e(page_title($kp)) . '</a>';
                $tree($kp, $depth + 1);
                echo '</li>';
            }
            echo '</ul>';
        };
        foreach ($MENU as $g):
            $heads = isset($g['href']) ? array_merge([$g['href']], $g['items']) : $g['items']; ?>
          <section>
            <h2><?php echo e(t($g['key'])); ?></h2>
            <ul>
              <?php foreach ($heads as $hp): if (isset($listed[$hp])) continue; $listed[$hp] = true; ?>
              <li><a href="<?php echo e(url($hp)); ?>"><?php echo e(page_title($hp)); ?></a><?php if ($hp !== '') $tree($hp, 1); ?></li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endforeach; ?>
        <section>
          <h2><?php echo e(t('menu_home')); ?></h2>
          <ul>
            <?php foreach ($PAGES as $pp => $pi): if (isset($listed[$pp]) || $pp === '' || $pi['parent'] !== '') continue; $listed[$pp] = true; ?>
            <li><a href="<?php echo e(url($pp)); ?>"><?php echo e(page_title($pp)); ?></a><?php $tree($pp, 1); ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
      </div>
    </article>
    <?php
} else { /* 404 */
    ?>
    <article class="content">
      <p><?php echo e(t('not_found_text')); ?></p>
      <p><a class="btn" href="<?php echo e(url('')); ?>"><?php echo e(t('go_home')); ?></a>
         <a class="btn" href="<?php echo e(url('sitemap')); ?>"><?php echo e(t('sitemap')); ?></a>
         <button class="btn" type="button" data-search-open><?php echo e(t('search')); ?></button></p>
    </article>
    <?php
}
