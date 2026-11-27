<?php
/* SITEMAP PAGE (ru) – the layout is in includes/special-pages.php */
$page = [
    'lang'        => 'ru',
    'path'        => 'sitemap',
    'special'     => 'sitemap',
    'children'    => false,
];
require __DIR__ . '/../includes/bootstrap.php';
$page['title'] = t('sitemap');
$page['description'] = t('sitemap');

require SITE_ROOT . '/includes/header.php';
require SITE_ROOT . '/includes/special-pages.php';
require SITE_ROOT . '/includes/footer.php';
