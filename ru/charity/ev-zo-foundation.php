<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'ru',
    'path'        => 'charity/ev-zo-foundation',
    'title'       => 'Фонд Ev-Zo',
    'description' => 'Фонд «Эв Зо» поддерживает пациентов, страдающих онкологическими заболеваниями, и финансируется за счёт пожертвований, доходов от мероприятий и взносов своих членов.',
    'image'       => '/images/charity/ev-zo/ev-zo.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<div class="blocks">
<div class="blocks"><div class="row split"><div class="block media"><img alt="" data-nw="1374" decoding="async" height="1192" loading="lazy" src="/images/charity/ev-zo/ev-zo.jpg" width="800"/>
<p class="short">Эв Зо</p>
</div><div class="block"><p><strong>Фонд «Эв Зо» поддерживает пациентов, страдающих онкологическими заболеваниями, </strong> <strong>и финансируется за счёт пожертвований, доходов от мероприятий и взносов своих членов.</strong></p></div></div></div>
</div>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
