<?php
/* HOME – quick links shown above the home page content.
   Each entry: page path => is it the highlighted (gold) button? */
$QUICK = [
    'next-event'                          => true,
    'program-events/monthly-program'      => false,
    'program-events/educational-courses'  => false,
    'planetarium/science-cafe'            => false,
    'observatory/public-observations'     => false,
    'join-our-spacewalk'                  => false,
];
?>
<div class="wrap">
  <ul class="quick">
    <?php foreach ($QUICK as $qp => $primary): ?>
    <li><a href="<?php echo e(url($qp)); ?>"<?php if ($primary) echo ' class="primary"'; ?>><?php echo e(page_title($qp)); ?></a></li>
    <?php endforeach; ?>
    <li><a href="<?php echo e($LINKS['gift_shop']); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(t('gift_shop')); ?></a></li>
  </ul>
</div>
