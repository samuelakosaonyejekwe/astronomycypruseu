<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'ru',
    'path'        => 'projects/discoveries',
    'title'       => 'Открытия',
    'description' => 'Открытия – Планетарий и обсерватория «Китион»',
    'image'       => '/images/index-page/earthrise.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<h2><strong>ПРЕДВАРИТЕЛЬНЫЕ ОТКРЫТИЯ АСТЕРОИДОВ</strong></h2>
<h2><strong>ОТКРЫТЫ КОМАНДАМИ ПЛАНЕТАРИЯ И ОБСЕРВАТОРИИ «КИТИОН»</strong></h2>
<ol class="cols"><li><strong>Астероид 2019 AN16</strong></li><li><strong>Астероид 2020 JW8</strong></li><li><strong>Астероид 2020 PG26</strong></li><li><strong>Астероид 2020 KN13</strong></li><li><strong>Астероид 2021 CQ27 (734827)</strong></li><li><strong>Астероид 2021 PF72</strong></li><li><strong>Астероид 2021 CJ48</strong></li><li><strong>Астероид 2021 PZ188</strong></li><li><strong>Астероид 2021 UH123</strong></li><li><strong>Астероид 2024 RT48</strong></li></ol>
<div class="link-row"><p class="short"><a href="https://minorplanetcenter.net/db_search/show_object?object_id=2164" rel="noopener noreferrer" target="_blank">Центр малых планет (Minor Planet Center)</a></p><p class="short"><a href="https://ssd.jpl.nasa.gov/horizons.cgi#results" rel="noopener noreferrer" target="_blank">NASA JPL</a></p></div>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
