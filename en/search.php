<?php
/* SEARCH PAGE (en) – the layout is in includes/special-pages.php */
$page = [
    'lang'        => 'en',
    'path'        => 'search',
    'special'     => 'search',
    'children'    => false,
    'robots'      => 'noindex',
];
require __DIR__ . '/../includes/bootstrap.php';
$page['title'] = t('search');
$page['description'] = t('search');

require SITE_ROOT . '/includes/header.php';
require SITE_ROOT . '/includes/special-pages.php';
require SITE_ROOT . '/includes/footer.php';
