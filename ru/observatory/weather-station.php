<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'ru',
    'path'        => 'observatory/weather-station',
    'title'       => 'Метеостанция',
    'description' => 'Собранные данные используются нашими учёными для собственного прогноза погоды на основе местного оборудования.',
    'image'       => '/images/weather-station.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<div class="blocks">
<div class="row">
<div class="block"><h2><strong>МЕТЕОРОЛОГИЧЕСКАЯ СТАНЦИЯ</strong></h2>
<p class="short">Обсерватория оснащена собственной метеостанцией.</p>
<p>Она установлена и работает с 2014 года.</p>
<p>Собранные данные используются нашими учёными для собственного прогноза погоды на основе местного оборудования.</p>
<p>Установленные приборы включают термометр, барометр, <span lang="en">дождемер</span>, <span lang="en">анемометр</span> и гигрометр.</p>
<p class="short">Время также отслеживается в обсерватории тем же оборудованием.</p>
</div>
</div>
<div class="row">
<div class="block media"><img alt="" data-nw="972" decoding="async" height="462" loading="lazy" src="/images/weather-station.jpg" width="600"/></div>
</div>
<div class="row">
<div class="block">
<p>Кроме того, оперативные данные для прогноза погоды для обсерватории поступают от <a href="https://zoom.earth/places/cyprus/" rel="noopener noreferrer" target="_blank">спутников</a> и с сайта <a href="https://clearoutside.com/forecast/34.86/33.58" rel="noopener noreferrer" target="_blank">clearoutside.com</a>.</p>
<p>Кроме того, обсерватория сотрудничает с <a href="https://www.kitasweather.com/" rel="noopener noreferrer" target="_blank">Kitas Weather</a> и <a href="https://www.moa.gov.cy/moa/dm/dm.nsf/home_en/home_en?openform" rel="noopener noreferrer" target="_blank">Департаментом метеорологии Кипра</a>.</p></div>
</div>
</div>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
