/* ==========================================================================
   KITION PLANETARIUM & OBSERVATORY – site behaviour
   Menu, instant search, section filters, image viewer, moon phase, theme.
   Everything degrades gracefully: the site works without JavaScript.
   ========================================================================== */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---------- Theme toggle ---------- */
  $$('[data-theme-toggle]').forEach(function (b) {
    b.addEventListener('click', function () {
      var root = document.documentElement;
      var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
      root.setAttribute('data-theme', next);
      try { localStorage.setItem('kpo-theme', next); } catch (e) {}
    });
  });

  /* ---------- Main menu (click / keyboard / mobile) ---------- */
  var nav = $('.main-nav');
  var toggle = $('.menu-toggle');
  function closeAll(except) {
    $$('.menu-item.has-sub.is-open').forEach(function (li) {
      if (li !== except) { li.classList.remove('is-open'); $('.menu-link', li).setAttribute('aria-expanded', 'false'); }
    });
  }
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
  $$('.menu-item.has-sub').forEach(function (li) {
    var btn = $('.menu-link', li);
    btn.addEventListener('click', function (ev) {
      ev.stopPropagation();
      var open = !li.classList.contains('is-open');
      closeAll(li);
      li.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    li.addEventListener('keydown', function (ev) {
      var links = $$('.submenu a', li);
      var i = links.indexOf(document.activeElement);
      if (ev.key === 'Escape') { closeAll(); btn.focus(); }
      else if (ev.key === 'ArrowDown') {
        ev.preventDefault();
        if (!li.classList.contains('is-open')) btn.click();
        (links[i + 1] || links[0]).focus();
      } else if (ev.key === 'ArrowUp' && i > -1) {
        ev.preventDefault(); (links[i - 1] || btn).focus();
      }
    });
  });
  document.addEventListener('click', function (ev) { if (!ev.target.closest('.menu')) closeAll(); });
  document.addEventListener('focusin', function (ev) {
    var li = ev.target.closest && ev.target.closest('.menu-item.has-sub');
    if (window.matchMedia('(min-width: 1081px)').matches) closeAll(li);
  });

  /* ---------- Back to top ---------- */
  var top = $('[data-to-top]');
  if (top) {
    var ring = $('.to-top-ring .progress', top);
    var onScroll = function () {
      top.classList.toggle('is-on', window.scrollY > 600);
      if (ring) {
        var max = document.documentElement.scrollHeight - window.innerHeight;
        ring.style.strokeDashoffset = String(100 - (max > 0 ? Math.min(100, window.scrollY / max * 100) : 0));
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
    top.addEventListener('click', function () { window.scrollTo({ top: 0 }); $('#content').focus({ preventScroll: true }); });
  }

  /* ---------- External links: say they open a new tab ---------- */
  $$('a[target="_blank"]').forEach(function (a) {
    if (!a.getAttribute('rel')) a.setAttribute('rel', 'noopener noreferrer');
  });

  /* ---------- Instant search ---------- */
  var dlg = $('#search-dialog');
  var input = $('[data-search-input]');
  var list = $('[data-search-results]');
  var index = null, loading = null;
  function norm(s) {
    return (s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/ς/g, 'σ');
  }
  function loadIndex() {
    if (index) return Promise.resolve(index);
    if (loading) return loading;
    loading = fetch(input.getAttribute('data-index'), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        index = d.map(function (p) { p._t = norm(p.t); p._x = norm(p.x); return p; });
        return index;
      }).catch(function () { index = []; return index; });
    return loading;
  }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function snippet(p, terms) {
    var x = p.x, nx = p._x, pos = -1;
    for (var i = 0; i < terms.length && pos < 0; i++) pos = nx.indexOf(terms[i]);
    var start = Math.max(0, pos - 60);
    var s = (start > 0 ? '…' : '') + x.substr(start, 180) + (x.length > start + 180 ? '…' : '');
    return esc(s);
  }
  function search(q) {
    var terms = norm(q).split(/\s+/).filter(function (w) { return w.length > 1; });
    if (!terms.length) return [];
    var out = [];
    index.forEach(function (p) {
      var score = 0;
      for (var i = 0; i < terms.length; i++) {
        var inT = p._t.indexOf(terms[i]) > -1, inX = p._x.indexOf(terms[i]) > -1;
        if (!inT && !inX) return;
        score += inT ? 10 : 1;
      }
      out.push({ p: p, s: score });
    });
    out.sort(function (a, b) { return b.s - a.s; });
    return out.slice(0, 30).map(function (r) { return r.p; });
  }
  var sel = -1;
  function render(q) {
    if (!list) return;
    var res = search(q);
    sel = -1;
    if (!q || q.trim().length < 2) { list.innerHTML = ''; return; }
    if (!res.length) { list.innerHTML = '<li class="r-empty">' + esc(list.getAttribute('data-empty')) + '</li>'; return; }
    var terms = norm(q).split(/\s+/).filter(function (w) { return w.length > 1; });
    list.innerHTML = res.map(function (p) {
      return '<li><a href="' + esc(p.u) + '"><span class="r-crumb">' + esc(p.c || '') + '</span><span class="r-title">' + esc(p.t) + '</span><span class="r-snip">' + snippet(p, terms) + '</span></a></li>';
    }).join('');
  }
  function openSearch() {
    if (!dlg) return;
    if (typeof dlg.showModal === 'function') dlg.showModal(); else dlg.setAttribute('open', '');
    input.focus(); input.select();
    loadIndex().then(function () { render(input.value); });
  }
  $$('[data-search-open]').forEach(function (b) { b.addEventListener('click', openSearch); });
  $$('[data-search-close]').forEach(function (b) { b.addEventListener('click', function () { dlg.close(); }); });
  if (dlg) dlg.addEventListener('click', function (ev) { if (ev.target === dlg) dlg.close(); });
  if (input) {
    var timer;
    input.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(function () { loadIndex().then(function () { render(input.value); }); }, 120);
    });
    input.addEventListener('keydown', function (ev) {
      var links = $$('a', list);
      if (ev.key === 'ArrowDown' || ev.key === 'ArrowUp') {
        ev.preventDefault();
        if (!links.length) return;
        sel = ev.key === 'ArrowDown' ? Math.min(sel + 1, links.length - 1) : Math.max(sel - 1, 0);
        links.forEach(function (a, i) { a.classList.toggle('is-sel', i === sel); });
        links[sel].scrollIntoView({ block: 'nearest' });
      } else if (ev.key === 'Enter' && sel > -1 && links[sel]) {
        ev.preventDefault(); window.location.href = links[sel].href;
      }
    });
  }
  document.addEventListener('keydown', function (ev) {
    var tag = (ev.target.tagName || '').toLowerCase();
    if (ev.key === '/' && tag !== 'input' && tag !== 'textarea' && !ev.target.isContentEditable) { ev.preventDefault(); openSearch(); }
  });

  /* ---------- Section filter (sub-page cards) ---------- */
  $$('[data-filter-scope]').forEach(function (bar) {
    var scope = $(bar.getAttribute('data-filter-scope'));
    var inp = $('[data-filter-input]', bar);
    var group = '';
    function apply() {
      var q = norm(inp ? inp.value : '');
      $$('.card', scope).forEach(function (c) {
        var okG = !group || c.getAttribute('data-group') === group;
        var okQ = !q || norm(c.textContent).indexOf(q) > -1;
        c.hidden = !(okG && okQ);
      });
    }
    if (inp) inp.addEventListener('input', apply);
    $$('[data-filter-group]', bar).forEach(function (chip) {
      chip.addEventListener('click', function () {
        group = chip.getAttribute('data-filter-group');
        $$('[data-filter-group]', bar).forEach(function (c) { c.classList.toggle('is-on', c === chip); });
        apply();
      });
    });
  });

  /* ---------- Justified picture rows: equal heights, full width, no cropping ---------- */
  function ratioOf(el) {
    if (el.tagName === 'IFRAME') return 16 / 9;
    var w = +el.getAttribute('width') || el.naturalWidth || el.videoWidth || 0;
    var h = +el.getAttribute('height') || el.naturalHeight || el.videoHeight || 0;
    return (w > 0 && h > 0) ? w / h : 0;
  }
  function justify(row) {
    var promo = row.classList.contains('promo');
    $$(':scope > .block', row).forEach(function (b) {
      var m = $('img, video, iframe', b);
      var r = m ? ratioOf(m) : 0;
      if (!r) { if (m && m.tagName === 'IMG' && !m.complete) m.addEventListener('load', function () { justify(row); }, { once: true }); return; }
      var cap = Math.round(r * (promo ? 620 : 440));                 // height limit
      var nw = m.getAttribute('data-nw') ? +m.getAttribute('data-nw') : 0;
      if (nw) cap = Math.min(cap, nw);                                // never enlarge beyond real pixels
      var minW = b.textContent.trim() ? 170 : 90;                     // room for the caption words
      b.style.flexGrow = r.toFixed(3);
      b.style.flexBasis = Math.max(minW, Math.min(cap, Math.round(r * (promo ? 300 : 230)))) + 'px';
      b.style.maxWidth = Math.max(minW, cap) + 'px';
      if (nw) m.style.maxWidth = nw + 'px';
    });
  }
  $$('.row.gallery:not(.tiles)').filter(function (r) { return !r.classList.contains('captioned') || r.classList.contains('promo'); }).forEach(justify);

  /* ---------- Image viewer for content images that are not links ---------- */
  var lb = $('#lightbox');
  var imgs = $$('.content img').filter(function (im) { return !im.closest('a') && (im.naturalWidth > 300 || +im.getAttribute('width') > 300 || !im.getAttribute('width')); });
  var cur = 0;
  function show(i) {
    cur = (i + imgs.length) % imgs.length;
    var im = imgs[cur];
    var big = $('img', lb);
    big.src = im.currentSrc || im.src;
    big.alt = im.alt || '';
    $('figcaption', lb).textContent = im.alt || im.title || '';
  }
  if (lb && imgs.length) {
    imgs.forEach(function (im, i) {
      im.classList.add('zoomable');
      im.setAttribute('tabindex', '0');
      im.setAttribute('role', 'button');
      var open = function () { show(i); if (lb.showModal) lb.showModal(); else lb.setAttribute('open', ''); };
      im.addEventListener('click', open);
      im.addEventListener('keydown', function (ev) { if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); open(); } });
    });
    $('[data-lb-close]', lb).addEventListener('click', function () { lb.close(); });
    $('[data-lb-prev]', lb).addEventListener('click', function () { show(cur - 1); });
    $('[data-lb-next]', lb).addEventListener('click', function () { show(cur + 1); });
    lb.addEventListener('click', function (ev) { if (ev.target === lb) lb.close(); });
    lb.addEventListener('keydown', function (ev) {
      if (ev.key === 'ArrowLeft') show(cur - 1);
      if (ev.key === 'ArrowRight') show(cur + 1);
    });
    if (imgs.length < 2) { $('[data-lb-prev]', lb).hidden = true; $('[data-lb-next]', lb).hidden = true; }
  }

  /* ---------- Moon phase (calculated locally, no third-party script) ---------- */
  var moon = $('[data-moon]');
  if (moon) {
    var SYN = 29.530588853;
    var ref = Date.UTC(2000, 0, 6, 18, 14) / 86400000;      // known new moon
    var days = Date.now() / 86400000 - ref;
    var age = ((days % SYN) + SYN) % SYN;
    var phase = age / SYN;                                   // 0 new, .5 full
    var lit = (1 - Math.cos(2 * Math.PI * phase)) / 2;
    var names = (moon.getAttribute('data-moon-names') || '').split('|');
    var idx = Math.floor(phase * 8 + 0.5) % 8;
    var r = 46, k = Math.cos(2 * Math.PI * phase) * r;      // terminator x-radius
    var waxing = phase < 0.5;
    // lit part: outer half-circle on the lit side + terminator ellipse
    var sweepOuter = waxing ? 1 : 0;
    var sweepTerm = (waxing ? (k > 0 ? 0 : 1) : (k > 0 ? 1 : 0));
    var d = 'M0,' + (-r) + ' A' + r + ',' + r + ' 0 0,' + sweepOuter + ' 0,' + r +
            ' A' + Math.abs(k).toFixed(2) + ',' + r + ' 0 0,' + sweepTerm + ' 0,' + (-r) + ' Z';
    $('.moon-lit', moon).setAttribute('d', d);
    var info = $('.moon-info', moon);
    if (info) info.textContent = (names[idx] || '') + ' · ' + Math.round(lit * 100) + '% ' + moon.getAttribute('data-moon-lit');
    moon.setAttribute('title', (names[idx] || '') + ' – ' + age.toFixed(1) + ' ' + moon.getAttribute('data-moon-age'));
  }
})();
