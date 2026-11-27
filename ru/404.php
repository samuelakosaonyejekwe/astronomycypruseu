<?php
/* 404 PAGE (ru) – the layout is in includes/special-pages.php */
$page = [
    'lang'        => 'ru',
    'path'        => '404',
    'special'     => '404',
    'children'    => false,
    'robots'      => 'noindex',
];
require __DIR__ . '/../includes/bootstrap.php';
$page['title'] = t('not_found');
$page['description'] = t('not_found');
http_response_code(404);
require SITE_ROOT . '/includes/header.php';
require SITE_ROOT . '/includes/special-pages.php';
require SITE_ROOT . '/includes/footer.php';
