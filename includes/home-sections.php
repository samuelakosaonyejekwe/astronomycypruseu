<?php
/* HOME – one card per menu section, built from the menu in config.php. */
?>
<section class="wrap section-index" aria-labelledby="explore">
  <h2 class="home-section-title" id="explore"><?php echo e(t('explore')); ?></h2>
  <ul class="cards">
    <?php foreach ($MENU as $g):
        if ($g['key'] === 'menu_home') continue;
        $first = isset($g['href']) ? $g['href'] : $g['items'][0];
        $img = isset($PAGES[$first]['img']) ? $PAGES[$first]['img'] : '';
        $sub = array_slice($g['items'], 1, 4); ?>
    <li class="card">
      <a href="<?php echo e(url($first)); ?>"<?php if (!$img) echo ' class="no-img"'; ?>>
        <?php if ($img): ?><img src="<?php echo e($img); ?>" alt="" loading="lazy" decoding="async"><?php endif; ?>
        <span class="card-title"><?php echo e(t($g['key'])); ?></span>
        <?php if ($sub): ?><span class="card-desc"><?php echo e(implode(' · ', array_map('page_title', $sub))); ?></span><?php endif; ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</section>
