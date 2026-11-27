<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'en',
    'path'        => 'observatory/weather-station',
    'title'       => 'Weather Station',
    'description' => 'The collected data are used by our scientists for their own weather forecast based on the local equipment.',
    'image'       => '/images/weather-station.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<div class="blocks">

<p class="short">The observatory is equipped with its own weather station. </p>
<p>It has been installed and operating since 2014.</p>
<p>The collected data are used by our scientists for their own weather forecast based on the local equipment.</p>
<p>The instruments installed include a thermometer, a barometer, a r<span lang="en">ain gauge</span>, an <span lang="en">anemometer</span> and a hygrometer.</p>
<p>Time is also monitored at the Observatory by the same equipment. </p>
<div class="blocks"><div class="row split"><div class="block media"><img alt="" data-nw="972" decoding="async" height="462" loading="lazy" src="/images/weather-station.jpg" width="600"/></div><div class="block"><p>In addition live data is received for the weather forecast for the observatory from <a href="https://zoom.earth/places/cyprus/" rel="noopener noreferrer" target="_blank">satellites</a> and the <a href="https://clearoutside.com/forecast/34.86/33.58" rel="noopener noreferrer" target="_blank">clearoutside.com</a> website.</p><p>Furthermore, the observatory collaborates with <a href="https://www.kitasweather.com/" rel="noopener noreferrer" target="_blank">Kitas Weather</a> and the <a href="https://www.moa.gov.cy/moa/dm/dm.nsf/home_en/home_en?openform" rel="noopener noreferrer" target="_blank">Cyprus Department of Meteorology</a>.</p></div></div></div>
</div>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
