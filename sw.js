/* Service worker for the static copy: security headers on every page + redirects of old addresses */
var BASE = '/astronomycypruseu/';
var SEC = {"Content-Security-Policy": "default-src 'self'; img-src 'self' data: https:; media-src 'self'; script-src 'self'; style-src 'self'; frame-src https://www.google.com https://www.youtube.com https://www.youtube-nocookie.com; connect-src 'self'; base-uri 'self'; form-action 'self'; object-src 'none'; upgrade-insecure-requests; frame-ancestors 'self'", "X-Frame-Options": "SAMEORIGIN", "X-Content-Type-Options": "nosniff", "Referrer-Policy": "strict-origin-when-cross-origin", "Permissions-Policy": "geolocation=(), camera=(), microphone=(), payment=()"};
var MAP = null;
function map() {
  if (MAP) return Promise.resolve(MAP);
  return fetch(BASE + 'data/redirects.json').then(function (r) { return r.json(); }).then(function (m) { MAP = m; return m; }).catch(function () { return {}; });
}
self.addEventListener('install', function () { self.skipWaiting(); });
self.addEventListener('activate', function (e) { e.waitUntil(self.clients.claim()); });
self.addEventListener('fetch', function (e) {
  var req = e.request;
  if (req.method !== 'GET' || req.mode !== 'navigate') return;
  var url = new URL(req.url);
  if (url.origin !== location.origin || url.pathname.indexOf(BASE) !== 0) return;
  e.respondWith(map().then(function (m) {
    var rel = decodeURIComponent(url.pathname.slice(BASE.length));
    var to = m[rel];
    if (to && !/^en\/|^el\/|^ru\//.test(rel)) return Response.redirect(/^https?:/.test(to) ? to : location.origin + BASE + to, 301);
    return fetch(req).then(function (res) {
      if (res.type === 'opaqueredirect' || !res.ok && res.status === 0) return res;
      var h = new Headers(res.headers);
      Object.keys(SEC).forEach(function (k) { h.set(k, SEC[k]); });
      return new Response(res.body, { status: res.status, statusText: res.statusText, headers: h });
    });
  }));
});
