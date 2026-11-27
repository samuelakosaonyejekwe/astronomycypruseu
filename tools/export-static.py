"""Make the static copy of the website for GitHub Pages (branch gh-pages).
Start the preview server first (PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php), then:
    python3 tools/export-static.py . <folder of the gh-pages checkout> /astronomycypruseu/
"""
import os, re, sys, json, shutil, urllib.request

SITE, OUT, BASE = sys.argv[1], sys.argv[2], sys.argv[3]
HOST = 'http://localhost:8000'
LANGS = ['en', 'el', 'ru']
CSP = ("default-src 'self'; img-src 'self' data: https:; media-src 'self'; script-src 'self'; style-src 'self'; "
       "frame-src https://www.google.com https://www.youtube.com https://www.youtube-nocookie.com; connect-src 'self'; "
       "base-uri 'self'; form-action 'self'; object-src 'none'; upgrade-insecure-requests")
HEADERS = {
    'Content-Security-Policy': CSP + "; frame-ancestors 'self'",
    'X-Frame-Options': 'SAMEORIGIN',
    'X-Content-Type-Options': 'nosniff',
    'Referrer-Policy': 'strict-origin-when-cross-origin',
    'Permissions-Policy': 'geolocation=(), camera=(), microphone=(), payment=()',
}

def get(path):
    with urllib.request.urlopen(HOST + urllib.request.quote(path, safe='/?=&%'), timeout=60) as r:
        return r.read().decode('utf-8')

def internal(url):
    """/en/x.php -> en/x.html ; /en/ -> en/ ; /images/a.jpg -> images/a.jpg"""
    m = re.match(r'^/(en|el|ru)/(.*?)\.php(\?[^#]*)?(#.*)?$', url)
    if m:
        return f'{m.group(1)}/{m.group(2)}.html{m.group(3) or ""}{m.group(4) or ""}'
    return url[1:]

def rewrite(html, self_path):
    # attributes that hold site-root addresses
    html = re.sub(r'(\s(?:href|src|poster|action|data-index))="(/(?!/)[^"]*)"',
                  lambda m: f'{m.group(1)}="{internal(m.group(2))}"', html)
    # in-page anchors must keep the page (the <base> tag would send them to the site root)
    html = re.sub(r'(\shref)="(#[^"]*)"', lambda m: f'{m.group(1)}="{self_path}{m.group(2)}"', html)
    # base + noindex right after <head>
    html = html.replace('<head>', f'<head>\n<base href="{BASE}">\n<meta name="robots" content="noindex, nofollow">\n'
                        f'<meta http-equiv="Content-Security-Policy" content="{CSP}">\n'
                        '<meta name="referrer" content="strict-origin-when-cross-origin">\n'
                        '<script src="assets/js/static.js"></script>', 1)
    return html

pages = [re.sub(r'^/en/|\.php$', '', p['u']) for p in json.load(open(os.path.join(SITE, 'data', 'search-en.json')))]
pages = ['' if p == '' else p for p in pages]
if os.path.isdir(OUT):
    for name in os.listdir(OUT):
        if name == '.git': continue
        p = os.path.join(OUT, name)
        shutil.rmtree(p) if os.path.isdir(p) else os.remove(p)
os.makedirs(OUT, exist_ok=True)
n = 0
for lang in LANGS:
    for path in pages + ['search', 'sitemap', '404']:
        src = f'/{lang}/' if path == '' else f'/{lang}/{path}.php'
        try:
            html = get(src)
        except urllib.error.HTTPError as e:
            html = e.read().decode('utf-8')      # 404 page body
        rel = f'{lang}/' + ('index.html' if path == '' else path + '.html')
        out = os.path.join(OUT, rel)
        os.makedirs(os.path.dirname(out), exist_ok=True)
        open(out, 'w').write(rewrite(html, rel)); n += 1

# GitHub Pages 404 page
shutil.copy(os.path.join(OUT, 'en', '404.html'), os.path.join(OUT, '404.html'))

# entrance: same language choice as the PHP index.php (saved choice, then browser language)
open(os.path.join(OUT, 'index.html'), 'w').write(f'''<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><base href="{BASE}">
<meta name="robots" content="noindex, nofollow"><title>Kition Planetarium &amp; Observatory</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
(function () {{
  var allowed = ['en', 'el', 'ru'], lang = 'en';
  try {{ var s = localStorage.getItem('kpo-lang'); if (allowed.indexOf(s) > -1) lang = s; else {{
    var l = (navigator.languages || [navigator.language || 'en']).join(',').toLowerCase();
    var best = 99; [['el','el'],['gr','el'],['ru','ru'],['en','en']].forEach(function (p) {{ var i = l.indexOf(p[0]); if (i > -1 && i < best) {{ best = i; lang = p[1]; }} }});
  }} }} catch (e) {{}}
  location.replace('{BASE}' + lang + '/');
}})();
</script></head>
<body><p><a href="en/">English</a> · <a href="el/">Ελληνικά</a> · <a href="ru/">Русский</a></p></body></html>
''')

# static files (same bytes as the PHP site)
for d in ('assets', 'images', 'docs', 'video'):
    src = os.path.join(SITE, d)
    if os.path.isdir(src):
        shutil.copytree(src, os.path.join(OUT, d), copy_function=os.link if os.stat(src).st_dev == os.stat(OUT).st_dev else shutil.copy2)
os.makedirs(os.path.join(OUT, 'data'), exist_ok=True)
for lang in LANGS:
    idx = json.load(open(os.path.join(SITE, 'data', f'search-{lang}.json')))
    for p in idx: p['u'] = internal(p['u'])
    json.dump(idx, open(os.path.join(OUT, 'data', f'search-{lang}.json'), 'w'), ensure_ascii=False, separators=(',', ':'))
# ---- old addresses: map + stub pages + service worker + 404 fallback ----
import subprocess
legacy = json.loads(subprocess.run(['php', '-r', 'echo json_encode(require "data/redirects.php");'], cwd=SITE,
                                   capture_output=True, text=True, check=True).stdout)
def target(path):
    if path is None: return None
    if re.match(r'^https?://', path): return path
    return 'en/' if path == '' else f'en/{path}.html'
redirects = {}
for p in pages:
    if p == '': continue
    for k in (p + '.html', p + '.htm', p):
        redirects[k] = target(p)
for k, v in legacy.items():
    t = target(v)
    if not t: continue
    for kk in (k + '.html', k + '.htm', k):
        redirects.setdefault(kk, t)
json.dump(redirects, open(os.path.join(OUT, 'data', 'redirects.json'), 'w'), separators=(',', ':'))
stubs = 0
for k, t in redirects.items():
    if not re.search(r'\.html?$', k): continue
    f = os.path.join(OUT, k)
    if os.path.exists(f) or k in ('index.html', '404.html'): continue
    os.makedirs(os.path.dirname(f) or OUT, exist_ok=True)
    href = t if t.startswith('http') else BASE + t
    open(f, 'w').write(f'''<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Moved</title>
<meta name="robots" content="noindex">
<meta http-equiv="refresh" content="0; url={href}">
<link rel="canonical" href="{href}">
</head><body><p>This page has moved: <a href="{href}">{href}</a></p></body></html>
''')
    stubs += 1
js_dir = os.path.join(OUT, 'assets', 'js')
open(os.path.join(js_dir, 'static.js'), 'w').write(f'''/* Static copy only: protection against framing + security headers via service worker */
(function () {{
  if (window.top !== window.self) {{ try {{ window.top.location = window.self.location; }} catch (e) {{ document.documentElement.style.display = 'none'; }} }}
  if ('serviceWorker' in navigator) {{
    window.addEventListener('load', function () {{ navigator.serviceWorker.register('{BASE}sw.js', {{ scope: '{BASE}' }}).catch(function () {{}}); }});
  }}
}})();
''')
open(os.path.join(js_dir, 'legacy.js'), 'w').write(f'''/* 404 page: send old addresses (astronomycyprus.eu/.../*.html) to their new page */
(function () {{
  var base = '{BASE}', path = decodeURIComponent(location.pathname);
  if (path.indexOf(base) !== 0) return;
  var rel = path.slice(base.length).replace(/^\\/+/, '');
  fetch(base + 'data/redirects.json').then(function (r) {{ return r.json(); }}).then(function (m) {{
    var to = m[rel] || m[rel.replace(/\\.(html?|php)$/i, '')] || m[rel.toLowerCase()];
    if (to) location.replace(/^https?:/.test(to) ? to : base + to);
  }}).catch(function () {{}});
}})();
''')
open(os.path.join(OUT, 'sw.js'), 'w').write(f'''/* Service worker for the static copy: security headers on every page + redirects of old addresses */
var BASE = '{BASE}';
var SEC = {json.dumps(HEADERS)};
var MAP = null;
function map() {{
  if (MAP) return Promise.resolve(MAP);
  return fetch(BASE + 'data/redirects.json').then(function (r) {{ return r.json(); }}).then(function (m) {{ MAP = m; return m; }}).catch(function () {{ return {{}}; }});
}}
self.addEventListener('install', function () {{ self.skipWaiting(); }});
self.addEventListener('activate', function (e) {{ e.waitUntil(self.clients.claim()); }});
self.addEventListener('fetch', function (e) {{
  var req = e.request;
  if (req.method !== 'GET' || req.mode !== 'navigate') return;
  var url = new URL(req.url);
  if (url.origin !== location.origin || url.pathname.indexOf(BASE) !== 0) return;
  e.respondWith(map().then(function (m) {{
    var rel = decodeURIComponent(url.pathname.slice(BASE.length));
    var to = m[rel];
    if (to && !/^en\\/|^el\\/|^ru\\//.test(rel)) return Response.redirect(/^https?:/.test(to) ? to : location.origin + BASE + to, 301);
    return fetch(req).then(function (res) {{
      if (res.type === 'opaqueredirect' || !res.ok && res.status === 0) return res;
      var h = new Headers(res.headers);
      Object.keys(SEC).forEach(function (k) {{ h.set(k, SEC[k]); }});
      return new Response(res.body, {{ status: res.status, statusText: res.statusText, headers: h }});
    }});
  }}));
}});
''')
# 404 page also runs the old-address lookup
p404 = os.path.join(OUT, '404.html')
open(p404, 'w').write(open(p404).read().replace('</head>', '<script src="assets/js/legacy.js"></script>\n</head>', 1))
print('redirect entries', len(redirects), 'stub pages', stubs)
open(os.path.join(OUT, 'robots.txt'), 'w').write('User-agent: *\nDisallow: /\n')
open(os.path.join(OUT, '.nojekyll'), 'w').write('')
open(os.path.join(OUT, 'README.md'), 'w').write(
    '# Kition Planetarium & Observatory – static preview\n\n'
    'This branch is a static copy of the PHP website on the `main` branch, generated for GitHub Pages.\n'
    'Edit the PHP site on `main`; this copy is regenerated from it.\n')
print('pages exported', n)
