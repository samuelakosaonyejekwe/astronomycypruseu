<?php
/* SEARCH PAGE (ru) – the layout is in includes/special-pages.php */
$page = [
    'lang'        => 'ru',
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
