<?php
/* ==========================================================================
   FOOTER – shared by every page.
   Also lists the sub-pages of the current page ("In this section").
   ========================================================================== */
$kids = !empty($page['children']) ? children_of($page['path']) : [];
if ($kids):
    $groups = [];
    foreach ($kids as $kp => $ki) {
        $g = isset($ki['group']) ? $ki['group'] : '';
        $groups[$g] = true;
    }
    $groups = array_keys($groups);
?>
<section class="section-index wrap" aria-labelledby="in-section">
  <div class="section-index-head">
    <h2 id="in-section"><?php echo e(t('in_this_section')); ?> <span class="count">(<?php echo count($kids); ?>)</span></h2>
    <?php if (count($kids) > 6): ?>
    <div class="filter-bar" data-filter-scope="#child-cards">
      <label class="visually-hidden" for="child-filter"><?php echo e(t('filter')); ?></label>
      <input id="child-filter" type="search" placeholder="<?php echo e(t('filter')); ?>" data-filter-input>
      <?php if (count($groups) > 1): ?>
      <div class="chips" role="group">
        <button type="button" class="chip is-on" data-filter-group=""><?php echo e(t('all')); ?></button>
        <?php foreach ($groups as $g): if ($g === '') continue; ?>
        <button type="button" class="chip" data-filter-group="<?php echo e($g); ?>"><?php echo e($g); ?></button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
  <ul class="cards" id="child-cards">
    <?php foreach ($kids as $kp => $ki): ?>
    <li class="card" data-group="<?php echo e(isset($ki['group']) ? $ki['group'] : ''); ?>">
      <a href="<?php echo e(url($kp)); ?>"<?php if (empty($ki['img'])) echo ' class="no-img"'; ?>>
        <?php if (!empty($ki['img'])): ?><img src="<?php echo e($ki['img']); ?>" alt="" loading="lazy" decoding="async"><?php endif; ?>
        <span class="card-title"><?php echo e(page_title($kp)); ?></span>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>
</main>

<footer class="site-footer">
  <div class="wrap footer-widgets">
    <a class="widget" href="<?php echo e($LINKS['gift_shop']); ?>" target="_blank" rel="noopener noreferrer">
      <img src="/images/gift-shop/visit-our-gift-shop-here.jpg" alt="<?php echo e(t('gift_shop_visit')); ?>" width="150" height="139" loading="lazy">
      <span><?php echo e(t('gift_shop')); ?></span>
    </a>
    <a class="widget" href="<?php echo e($LINKS['online_shop']); ?>" target="_blank" rel="noopener noreferrer">
      <img src="/images/gift-shop/visit-our-kition-online-shop.jpg" alt="<?php echo e(t('online_shop_alt')); ?>" width="150" height="97" loading="lazy">
      <span><?php echo e(t('online_shop')); ?></span>
    </a>
    <a class="widget" href="<?php echo e(url('quiz')); ?>">
      <img src="/images/quiz.jpg" alt="" width="150" height="150" loading="lazy">
      <span><?php echo e(t('quiz')); ?></span>
    </a>
    <a class="widget" href="<?php echo e($LINKS['star_chart']); ?>" target="_blank" rel="noopener noreferrer">
      <img src="/images/star-chart.jpg" alt="" width="150" height="150" loading="lazy">
      <span><?php echo e(t('star_chart')); ?></span>
    </a>
    <a class="widget moon-widget" href="<?php echo e($LINKS['moon_phase']); ?>" target="_blank" rel="noopener noreferrer" data-moon data-moon-names="<?php echo e(t('moon_names')); ?>" data-moon-age="<?php echo e(t('moon_age')); ?>" data-moon-lit="<?php echo e(t('moon_lit')); ?>">
      <svg class="moon" viewBox="-50 -50 100 100" width="110" height="110" aria-hidden="true"><circle r="46" class="moon-dark"/><path class="moon-lit" d=""/></svg>
      <span><?php echo e(t('moon_phase')); ?></span>
      <small class="moon-info" aria-live="polite"></small>
    </a>
    <div class="widget map-widget">
      <iframe src="<?php echo e($LINKS['map_embed']); ?>" title="<?php echo e(t('find_us')); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
      <span><?php echo e(t('find_us')); ?></span>
    </div>
  </div>

  <div class="wrap footer-bottom">
    <ul class="social">
      <li><a href="<?php echo e($LINKS['facebook']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('facebook')); ?></a></li>
      <li><a href="<?php echo e($LINKS['instagram']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('instagram')); ?></a></li>
      <li><a href="<?php echo e($LINKS['youtube']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('youtube')); ?></a></li>
      <li><a href="<?php echo e($LINKS['linkedin']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('linkedin')); ?></a></li>
      <li><a href="<?php echo e($LINKS['tripadvisor']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('tripadvisor_short')); ?></a></li>
    </ul>
    <p><?php echo e(sprintf(t('copyright'), date('Y'))); ?><br><?php echo e(t('developed')); ?></p>
    <p class="footer-links">
      <a href="<?php echo e(url('pages/legal-notices')); ?>"><?php echo e(t('legal_notices')); ?></a>
      <span aria-hidden="true">·</span>
      <a href="<?php echo e(url('sitemap')); ?>"><?php echo e(t('sitemap')); ?></a>
      <span aria-hidden="true">·</span>
      <a href="<?php echo e(url('contact-us')); ?>"><?php echo e(t('contact_us')); ?></a>
    </p>
  </div>
</footer>

<button type="button" class="to-top" data-to-top aria-label="<?php echo e(t('back_to_top')); ?>" title="<?php echo e(t('back_to_top')); ?>">
  <svg class="to-top-ring" viewBox="0 0 52 52" aria-hidden="true"><circle class="track" cx="26" cy="26" r="23"/><circle class="progress" cx="26" cy="26" r="23" pathLength="100"/></svg>
  <svg class="to-top-arrow" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 4.5 4.6 11.9l1.5 1.5 4.8-4.8V20h2.2V8.6l4.8 4.8 1.5-1.5z"/></svg>
</button>

<!-- Image viewer -->
<dialog class="lightbox" id="lightbox" aria-label="<?php echo e(t('open_image')); ?>">
  <button type="button" class="lb-close icon-btn" data-lb-close aria-label="<?php echo e(t('close')); ?>">✕</button>
  <button type="button" class="lb-prev icon-btn" data-lb-prev aria-label="<?php echo e(t('prev')); ?>">‹</button>
  <figure><img alt=""><figcaption></figcaption></figure>
  <button type="button" class="lb-next icon-btn" data-lb-next aria-label="<?php echo e(t('next')); ?>">›</button>
</dialog>
</body>
</html>
