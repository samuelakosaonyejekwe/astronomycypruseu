<?php
/* ==========================================================================
   SITE ENTRANCE  –  sends visitors to their language:
   1. the language they chose before (cookie), else
   2. their browser language (Greek / Russian), else
   3. English.
   ========================================================================== */
$allowed = ['en', 'el', 'ru'];
$lang = 'en';
if (isset($_COOKIE['kpo_lang']) && in_array($_COOKIE['kpo_lang'], $allowed, true)) {
    $lang = $_COOKIE['kpo_lang'];
} elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $al = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $best = 99;
    foreach (['el' => 'el', 'gr' => 'el', 'ru' => 'ru', 'en' => 'en'] as $code => $target) {
        $pos = strpos($al, $code);
        if ($pos !== false && $pos < $best) { $best = $pos; $lang = $target; }
    }
}
header('Vary: Accept-Language, Cookie');
header('Location: /' . $lang . '/', true, 302);
exit;
