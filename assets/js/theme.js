/* Applies the saved light/dark theme before the page is drawn (avoids a flash). */
(function () {
  try {
    var t = localStorage.getItem('kpo-theme');
    if (!t) t = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', t);
  } catch (e) { /* storage blocked: keep default */ }
})();
