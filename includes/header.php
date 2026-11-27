<?php
/* ==========================================================================
   HEADER – shared by every page (top bar, banner, main menu, page title).
   Menu items are set in includes/config.php, their names in includes/lang/.
   ========================================================================== */
$cur_path  = $page['path'];
$cur_group = menu_group_of($cur_path);
$is_home   = ($cur_path === '');
$doc_title = $is_home ? t('site_name') : $page['title'] . ' – ' . t('site_name');
$self_url  = url($cur_path);
?><!DOCTYPE html>
<html lang="<?php echo e($LANGUAGES[CURRENT_LANG][2]); ?>" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($doc_title); ?></title>
<meta name="description" content="<?php echo e($page['description']); ?>">
<meta property="og:title" content="<?php echo e($doc_title); ?>">
<meta property="og:description" content="<?php echo e($page['description']); ?>">
<meta property="og:image" content="<?php echo e(abs_url($page['image'])); ?>">
<meta property="og:url" content="<?php echo e(abs_url($self_url)); ?>">
<link rel="canonical" href="<?php echo e(abs_url($self_url)); ?>">
<meta property="og:type" content="website">
<meta name="theme-color" content="#0a1230">
<?php if (!empty($page['robots'])): ?><meta name="robots" content="<?php echo e($page['robots']); ?>">
<?php endif; ?>
<?php foreach ($LANGUAGES as $code => $L): if (page_exists($cur_path, $code)): ?>
<link rel="alternate" hreflang="<?php echo e($L[2]); ?>" href="<?php echo e(abs_url(url($cur_path, $code))); ?>">
<?php endif; endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?php echo e(abs_url('/')); ?>">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/site.css">
<script src="/assets/js/theme.js"></script>
<script src="/assets/js/site.js" defer></script>
</head>
<body class="<?php echo $is_home ? 'is-home' : 'is-page'; ?>" data-lang="<?php echo e(CURRENT_LANG); ?>">
<a class="skip-link" href="#content"><?php echo e(t('skip')); ?></a>

<header class="site-header">
  <!-- Top bar: social links, contact, donate, gift shop, language, theme -->
  <div class="topbar">
    <div class="wrap topbar-inner">
      <ul class="social" aria-label="<?php echo e(t('follow_us')); ?>">
        <li><a href="<?php echo e($LINKS['facebook']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e(t('facebook')); ?>"><img src="/images/facebook_logo.jpg" alt="<?php echo e(t('facebook')); ?>" width="28" height="28"></a></li>
        <li><a href="<?php echo e($LINKS['instagram']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e(t('instagram')); ?>"><img src="/images/instagram_logo.jpg" alt="<?php echo e(t('instagram')); ?>" width="28" height="28"></a></li>
        <li><a href="<?php echo e($LINKS['youtube']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e(t('youtube')); ?>"><img src="/images/youtube_logo.jpg" alt="<?php echo e(t('youtube')); ?>" width="28" height="28"></a></li>
        <li><a href="<?php echo e($LINKS['linkedin']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e(t('linkedin')); ?>"><img src="/images/linkedin_logo.svg" alt="<?php echo e(t('linkedin')); ?>" width="28" height="28"></a></li>
        <li><a href="<?php echo e($LINKS['tripadvisor']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo e(t('tripadvisor')); ?>"><img src="/images/tripadvisor.JPG" alt="<?php echo e(t('tripadvisor_short')); ?>" width="28" height="28"></a></li>
      </ul>
      <ul class="utility">
        <li><a href="<?php echo e(url('contact-us')); ?>"><?php echo e(t('contact_us')); ?></a></li>
        <li><a class="btn-donate" href="<?php echo e(url('donations')); ?>"><?php echo e(t('donate_now')); ?></a></li>
        <li><a href="<?php echo e($LINKS['gift_shop']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('gift_shop')); ?></a></li>
      </ul>
      <div class="topbar-tools">
        <nav class="lang-switch" aria-label="<?php echo e(t('language')); ?>">
          <?php foreach ($LANGUAGES as $code => $L):
              $target = page_exists($cur_path, $code) ? url($cur_path, $code) : url('', $code); ?>
            <a href="<?php echo e($target); ?>" hreflang="<?php echo e($L[2]); ?>" lang="<?php echo e($L[2]); ?>" title="<?php echo e($L[0]); ?>"<?php if ($code === CURRENT_LANG) echo ' aria-current="true" class="active"'; ?>><?php echo e($L[1]); ?></a>
          <?php endforeach; ?>
        </nav>
        <button type="button" class="icon-btn" data-theme-toggle aria-label="<?php echo e(t('theme')); ?>" title="<?php echo e(t('theme')); ?>">
          <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3a9 9 0 1 0 9 9 7 7 0 0 1-9-9z"/></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Banner (the original site's header artwork) -->
  <div class="banner">
    <a class="brand" href="<?php echo e(url('')); ?>">
      <img src="/images/index-page/earthrise.jpg" width="1920" height="230" alt="<?php echo e(t('site_name') . ' – ' . t('site_place')); ?>">
      <span class="visually-hidden"><?php echo e(t('site_name')); ?></span>
    </a>
  </div>

  <!-- Main menu -->
  <div class="navbar" id="navbar">
    <div class="wrap navbar-inner">
      <button type="button" class="menu-toggle" aria-controls="main-menu" aria-expanded="false">
        <span class="burger" aria-hidden="true"></span><span><?php echo e(t('menu')); ?></span>
      </button>
      <nav class="main-nav" aria-label="<?php echo e(t('menu')); ?>">
        <ul id="main-menu" class="menu">
          <?php foreach ($MENU as $i => $g):
              $active = ($i === $cur_group) ? ' is-active' : '';
              if (empty($g['items'])): ?>
            <li class="menu-item<?php echo $active; ?>"><a class="menu-link" href="<?php echo e(url($g['href'])); ?>"><?php echo e(t($g['key'])); ?></a></li>
          <?php else: ?>
            <li class="menu-item has-sub<?php echo $active; ?>">
              <button type="button" class="menu-link" aria-expanded="false" aria-controls="sub-<?php echo $i; ?>"><?php echo e(t($g['key'])); ?><svg class="chev" viewBox="0 0 12 12" width="10" height="10" aria-hidden="true"><path fill="currentColor" d="M6 8.5 1.5 4h9z"/></svg></button>
              <ul class="submenu" id="sub-<?php echo $i; ?>">
                <?php if (isset($g['href'])): ?>
                <li><a href="<?php echo e(url($g['href'])); ?>"<?php if ($cur_path === $g['href']) echo ' aria-current="page"'; ?>><?php echo e(page_title($g['href'])); ?></a></li>
                <?php endif; ?>
                <?php foreach ($g['items'] as $p): ?>
                <li><a href="<?php echo e(url($p)); ?>"<?php if ($cur_path === $p) echo ' aria-current="page"'; ?>><?php echo e(page_title($p)); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php endif; endforeach; ?>
        </ul>
      </nav>
      <button type="button" class="search-open" data-search-open aria-haspopup="dialog">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 4a6 6 0 1 0 3.9 10.6l4.7 4.7 1.4-1.4-4.7-4.7A6 6 0 0 0 10 4zm0 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></svg>
        <span><?php echo e(t('search')); ?></span><kbd>/</kbd>
      </button>
    </div>
  </div>
</header>

<!-- Search dialog (instant search; works without JavaScript through search.php) -->
<dialog class="search-dialog" id="search-dialog" aria-label="<?php echo e(t('search')); ?>">
  <form class="search-form" action="<?php echo e(url('search')); ?>" method="get" role="search">
    <label class="visually-hidden" for="search-q"><?php echo e(t('search')); ?></label>
    <input id="search-q" name="q" type="search" maxlength="80" autocomplete="off" placeholder="<?php echo e(t('search_placeholder')); ?>" data-search-input data-index="/data/search-<?php echo e(CURRENT_LANG); ?>.json">
    <button type="button" class="icon-btn" data-search-close aria-label="<?php echo e(t('close')); ?>">✕</button>
  </form>
  <p class="search-hint"><?php echo e(t('search_hint')); ?></p>
  <ol class="search-results" data-search-results data-empty="<?php echo e(t('no_results')); ?>"></ol>
</dialog>

<main id="content" class="site-main" tabindex="-1">
<?php if (!$is_home): ?>
  <div class="page-head">
    <div class="wrap">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo e(url('')); ?>"><?php echo e(page_title('')); ?></a></li>
          <?php foreach (breadcrumb_chain($cur_path) as $bc): if ($bc === '') continue; ?>
          <li><a href="<?php echo e(url($bc)); ?>"><?php echo e(page_title($bc)); ?></a></li>
          <?php endforeach; ?>
          <li aria-current="page"><?php echo e($page['title']); ?></li>
        </ol>
      </nav>
      <h1 class="page-title"><?php echo e($page['title']); ?></h1>
    </div>
  </div>
<?php endif; ?>
<?php if (empty($page['translated'])): ?>
  <div class="wrap"><p class="notice" role="note"><?php echo e(t('not_translated')); ?></p></div>
<?php endif; ?>
