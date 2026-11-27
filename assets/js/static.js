/* Static copy only: protection against framing + security headers via service worker */
(function () {
  if (window.top !== window.self) { try { window.top.location = window.self.location; } catch (e) { document.documentElement.style.display = 'none'; } }
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () { navigator.serviceWorker.register('/astronomycypruseu/sw.js', { scope: '/astronomycypruseu/' }).catch(function () {}); });
  }
})();
