"""Make the static copy of the website for GitHub Pages (branch gh-pages).
Start the preview server first (PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php), then:
    python3 tools/export-static.py . <folder of the gh-pages checkout> /astronomycypruseu/
"""
import os, re, sys, json, shutil, urllib.request

SITE, OUT, BASE = sys.argv[1], sys.argv[2], sys.argv[3]
HOST = 'http://localhost:8000'
LANGS = ['en', 'el', 'ru']

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
    html = html.replace('<head>', f'<head>\n<base href="{BASE}">\n<meta name="robots" content="noindex, nofollow">', 1)
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
open(os.path.join(OUT, 'robots.txt'), 'w').write('User-agent: *\nDisallow: /\n')
open(os.path.join(OUT, '.nojekyll'), 'w').write('')
open(os.path.join(OUT, 'README.md'), 'w').write(
    '# Kition Planetarium & Observatory – static preview\n\n'
    'This branch is a static copy of the PHP website on the `main` branch, generated for GitHub Pages.\n'
    'Edit the PHP site on `main`; this copy is regenerated from it.\n')
print('pages exported', n)
