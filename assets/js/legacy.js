/* 404 page: send old addresses (astronomycyprus.eu/.../*.html) to their new page */
(function () {
  var base = '/astronomycypruseu/', path = decodeURIComponent(location.pathname);
  if (path.indexOf(base) !== 0) return;
  var rel = path.slice(base.length).replace(/^\/+/, '');
  fetch(base + 'data/redirects.json').then(function (r) { return r.json(); }).then(function (m) {
    var to = m[rel] || m[rel.replace(/\.(html?|php)$/i, '')] || m[rel.toLowerCase()];
    if (to) location.replace(/^https?:/.test(to) ? to : base + to);
  }).catch(function () {});
})();
