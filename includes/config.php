<?php
/* ==========================================================================
   SITE CONFIGURATION
   Edit this file to change site-wide settings, contact details and the menu.
   ========================================================================== */

if (!defined('SITE_ROOT')) {
    define('SITE_ROOT', dirname(__DIR__));
}

/* Public address of the live site (used for search engines and social media).
   Change it if the site moves to another domain. */
define('SITE_URL', 'https://astronomycyprus.eu');

/* Site name, as written on the original site */
define('SITE_NAME', 'Kition Planetarium & Observatory');

/* Languages: code => [native label, short label, HTML lang attribute] */
$LANGUAGES = [
    'en' => ['English',  'EN', 'en'],
    'el' => ['Ελληνικά', 'ΕΛ', 'el'],
    'ru' => ['Русский',  'РУ', 'ru'],
];
define('DEFAULT_LANG', 'en');

/* External links used in the header and footer (from the original site) */
$LINKS = [
    'facebook'    => 'https://www.facebook.com/KITIONPLANETARIUMOBSERVATORY',
    'fb_group'    => 'https://www.facebook.com/groups/kition',
    'instagram'   => 'https://www.instagram.com/kpocyprus/',
    'youtube'     => 'https://www.youtube.com/@cyprusplanetarium',
    'tripadvisor' => 'https://www.tripadvisor.com/Attraction_Review-g1906037-d9765562-Reviews-Kition_Planetarium_Observatory_Cyprus-Kiti_Larnaka_District.html',
    'gift_shop'   => 'https://astronomycyprus.eu/gift-shop/shop/',
    'online_shop' => 'https://www.instagram.com/onlineshopkition/',
    'star_chart'  => 'https://stellarium-web.org/',
    'moon_phase'  => 'https://www.moonconnection.com/moon_phases.phtml',
    'map_embed'   => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2709.2756356046416!2d33.57574261462543!3d34.84404558303632!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14e09b5f9f11e867%3A0x72c8749395ddf02!2sKITION%20PLANETARIUM%20%26%20OBSERVATORY!5e1!3m2!1sen!2s!4v1605702887267!5m2!1sen!2s',
    'clear_outside'     => 'https://clearoutside.com/forecast/34.86/33.58',
    'clear_outside_img' => 'https://clearoutside.com/forecast_image_large/34.86/33.58/forecast.png',
];

/* --------------------------------------------------------------------------
   MAIN MENU  (same structure as the original site)
   Each item: page path (without language folder and without .php).
   Labels come from includes/lang/<language>.php  ->  $NAV_LABELS
   A group's first entry is the group's own heading key.
   -------------------------------------------------------------------------- */
$MENU = [
    ['key' => 'menu_home', 'href' => '', 'items' => [
        'next-event', 'newsletter', 'join-our-spacewalk', 'donations',
    ]],
    ['key' => 'menu_about', 'items' => [
        'about-us', 'about-us/history', 'about-us/mission-vision',
        'about-us/director-message', 'about-us/mass-media', 'about-us/staff',
    ]],
    ['key' => 'menu_planetarium', 'items' => [
        'planetarium', 'planetarium/dome', 'planetarium/theater',
        'planetarium/exhibits', 'planetarium/science-cafe', 'planetarium/planetarium-shows',
    ]],
    ['key' => 'menu_observatory', 'items' => [
        'observatory', 'observatory/optical-telescopes', 'observatory/radiotelescopes',
        'observatory/weather-station', 'observatory/public-observations',
    ]],
    ['key' => 'menu_projects', 'items' => [
        'projects', 'projects/international-projects', 'projects/national-projects',
        'projects/research', 'projects/scientific-publications', 'projects/discoveries',
    ]],
    ['key' => 'menu_program', 'items' => [
        'program-events/monthly-program', 'program-events/astronomical-phenomena',
        'program-events/educational-courses', 'program-events/school-visits',
        'program-events/birthday-parties', 'program-events/private-programs',
        'program-events/customized-events',
    ]],
    ['key' => 'menu_gallery', 'items' => [
        'gallery/astrophotography', 'gallery/events', 'gallery/kids-astroart',
    ]],
    ['key' => 'menu_charity', 'items' => [
        'charity', 'charity/christina-a-apostolou-foundation', 'charity/anti-drug-society',
        'charity/anti-cancer-society', 'charity/pasykaf', 'charity/ev-zo-foundation',
        'charity/aspida-tou-aianta', 'charity/kition-2014',
    ]],
    ['key' => 'menu_partners',   'href' => 'partners',   'items' => []],
    ['key' => 'menu_supporters', 'href' => 'supporters', 'items' => []],
];
