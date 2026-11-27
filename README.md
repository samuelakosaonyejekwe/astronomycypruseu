# Kition Planetarium & Observatory – website

A rebuild of astronomycyprus.eu in three languages (English, Greek and Russian), in plain
PHP that can be edited in Dreamweaver.

* **Preview:** `PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php`, then open http://localhost:8000
* **Editing guide:** see [DREAMWEAVER-GUIDE.md](DREAMWEAVER-GUIDE.md)
* **Static copy on GitHub Pages:** https://samuelakosaonyejekwe.github.io/astronomycypruseu/ (branch `gh-pages`).
  To refresh it after editing: start the preview server, check out `gh-pages` in another folder
  (`git worktree add ../site-pages gh-pages`), run
  `python3 tools/export-static.py . ../site-pages /astronomycypruseu/`, then commit and push that folder.
* **After editing pages:** `php tools/rebuild-index.php` (refreshes the search, the page list, `sitemap.xml` and `robots.txt`)

What it contains:
* 234 pages per language: every live page of the original site, plus the old `.htm` pages that
  were still online. Links that were broken on the original site point to the page that now holds
  that content.
* Responsive layout with a dropdown menu (mouse, touch and keyboard), mobile menu, breadcrumbs,
  "In this section" cards with a filter and year chips, instant site search (press `/`), a site
  map, an image viewer, a light/dark theme, a locally calculated moon phase and a "back to top" button.
* Security: output escaping, a strict Content-Security-Policy, no inline scripts, protected
  internal folders, no third-party scripts (only the Google map and the Clear Outside forecast
  image, both from the original site), and 301 redirects from every old address.
* Requirements: PHP 7.4+ (8.x recommended). On the live server, Apache with mod_rewrite and
  mod_headers.
