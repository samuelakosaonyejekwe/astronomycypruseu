<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'ru',
    'path'        => 'projects/research',
    'title'       => 'Исследования',
    'description' => 'ПЛАНЕТАРИЙ И ОБСЕРВАТОРИЯ «КИТИОН» инвестирует в научные исследования а также поощряет учащихся и широкую публику к участию в проектах гражданской науки.',
    'image'       => '/images/index-page/earthrise.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<h2><strong>ИССЛЕДОВАТЕЛЬСКИЕ</strong> ПРОЕКТЫ ПЛАНЕТАРИЯ И ОБСЕРВАТОРИИ «КИТИОН»</h2>
<p>ПЛАНЕТАРИЙ И ОБСЕРВАТОРИЯ «КИТИОН» инвестирует в научные исследования а также поощряет учащихся и широкую публику к участию в проектах гражданской науки.</p>
<ul class="label-row"><li>ПОИСК АСТЕРОИДОВ</li><li>ОКОЛОЗЕМНЫЕ ОБЪЕКТЫ</li><li>МЕТЕОРНЫЕ ПОТОКИ</li><li>МЕТЕОРИТЫ</li><li>ПЕРЕМЕННЫЕ ЗВЁЗДЫ</li></ul>
<h2>РАДИОАСТРОНОМИЯ: ПРОЕКТ NASA RADIO JOVE</h2>
<p class="short">АРХЕОАСТРОНОМИЯ</p>
<p class="short">ИЗМЕРЕНИЯ СВЕТОВОГО ЗАГРЯЗНЕНИЯ</p>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
