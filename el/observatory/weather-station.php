<?php
/* =====================================================================
   PAGE SETTINGS – change the title or description here.
   Edit the page text in Dreamweaver between CONTENT START and CONTENT END.
   ===================================================================== */
$page = [
    'lang'        => 'el',
    'path'        => 'observatory/weather-station',
    'title'       => 'Μετεωρολογικός Σταθμός',
    'description' => 'Τα δεδομένα που συλλέγονται χρησιμοποιούνται από τους επιστήμονές μας για τη δική τους πρόγνωση καιρού με βάση τον τοπικό εξοπλισμό.',
    'image'       => '/images/weather-station.jpg',
    'translated'  => true,
];
require __DIR__ . '/../../includes/bootstrap.php';
require SITE_ROOT . '/includes/header.php';
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
<div class="blocks">
<h2><strong>ΜΕΤΕΩΡΟΛΟΓΙΚΟΣ ΣΤΑΘΜΟΣ</strong></h2>
<p class="short">Το αστεροσκοπείο διαθέτει τον δικό του μετεωρολογικό σταθμό.</p>
<p>Έχει εγκατασταθεί και λειτουργεί από το 2014.</p>
<p>Τα δεδομένα που συλλέγονται χρησιμοποιούνται από τους επιστήμονές μας για τη δική τους πρόγνωση καιρού με βάση τον τοπικό εξοπλισμό.</p>
<p>Τα εγκατεστημένα όργανα περιλαμβάνουν ένα θερμόμετρο, ένα βαρόμετρο, ένα <span lang="en">βροχόμετρο</span>, ένα <span lang="en">ανεμόμετρο</span> και ένα υγρόμετρο.</p>
<p>Ο καιρός παρακολουθείται επίσης στο Αστεροσκοπείο από τον ίδιο εξοπλισμό.</p>
<div class="row">
<div class="block media"><img alt="" data-nw="972" decoding="async" height="462" loading="lazy" src="/images/weather-station.jpg" width="600"/></div>
</div>
<p>Επιπλέον, λαμβάνονται ζωντανά δεδομένα για την πρόγνωση καιρού του αστεροσκοπείου από <a href="https://zoom.earth/places/cyprus/" rel="noopener noreferrer" target="_blank">δορυφόρους</a> και τον ιστότοπο <a href="https://clearoutside.com/forecast/34.86/33.58" rel="noopener noreferrer" target="_blank">clearoutside.com</a>.</p>
<p>Επιπλέον, το αστεροσκοπείο συνεργάζεται με το <a href="https://www.kitasweather.com/" rel="noopener noreferrer" target="_blank">Kitas Weather</a> και το <a href="https://www.moa.gov.cy/moa/dm/dm.nsf/home_en/home_en?openform" rel="noopener noreferrer" target="_blank">Τμήμα Μετεωρολογίας Κύπρου</a>.</p>
</div>
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require SITE_ROOT . '/includes/footer.php'; ?>
