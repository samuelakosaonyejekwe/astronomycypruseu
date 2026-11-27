# Editing the Kition Planetarium & Observatory website in Dreamweaver

The website is plain **PHP + HTML + CSS**. There is no database and no build step. Each page is
one `.php` file that you open, edit and save in Dreamweaver.

---

## 1. Set up the site in Dreamweaver (one time)

1. **Site → New Site…**
   - *Site Name:* `Kition Planetarium & Observatory`
   - *Local Site Folder:* this folder (the one that contains `index.php`, `en`, `el` and `ru`).
2. **Servers → +** (add a server)
   - *Testing server:* tick **Testing**. Choose *Local/Network*, set *Server Folder* to this folder
     and *Web URL* to `http://localhost:8000/`. Server model: **PHP MySQL** (MySQL isn't used,
     but this is the PHP option).
   - *Remote server (your hosting):* add your FTP/SFTP details here and tick **Remote**.
3. **Advanced Settings → Local Info:** set *Links relative to* **Site Root**. The site uses
   addresses like `/images/…` and `/en/…`.
4. To see pages exactly as visitors will, start the preview server (step 5) and use **Live View**.

## 2. Folder map

| Folder / file | What it is |
|---|---|
| `en/`, `el/`, `ru/` | The pages in **English**, **Greek** and **Russian**. Same file names in each. |
| `en/index.php` … | Home page of each language. |
| `includes/header.php` | Top bar, banner and main menu (shared by all pages). |
| `includes/footer.php` | Footer, "In this section" cards, image viewer (shared). |
| `includes/config.php` | **Main menu**, social links, map, shop links. |
| `includes/lang/en.php`, `el.php`, `ru.php` | **Interface text** (buttons, menu names, footer text) in each language. |
| `includes/home-top.php` | Quick-link buttons at the top of the home page. |
| `assets/css/site.css` | All styling: colours are variables at the top of the file. |
| `assets/js/site.js` | Menu, search, filters, image viewer, moon phase. |
| `images/`, `docs/` | Pictures, videos and PDFs (same paths as the original site). |
| `data/` | Generated page list and search index (see step 4). `data/redirects.php` maps old addresses. |
| `tools/rebuild-index.php` | Rebuilds the page list, the search index, `sitemap.xml` and `robots.txt`. |
| `sitemap.xml`, `robots.txt` | For search engines (generated – don't edit by hand). |

## 3. Edit a page

Open, for example, `en/about-us/history.php`. Every page looks like this:

```php
<?php
$page = [
    'lang'        => 'en',
    'path'        => 'about-us/history',
    'title'       => 'History',            // ← page title (top of page and browser tab)
    'description' => '…',                  // ← short summary for Google and social media
    'image'       => '/images/…',          // ← picture used when the page is shared
    'translated'  => true,                 // ← false shows a "not yet translated" note
];
require …bootstrap.php; require …header.php;
?>
<!-- ======================= CONTENT START ======================= -->
<article class="content">
   … the page text: edit freely in Design or Code view …
</article>
<!-- ======================== CONTENT END ======================== -->
<?php require …footer.php; ?>
```

* Change **only the part between `CONTENT START` and `CONTENT END`**, plus the `title` and
  `description` if needed.
* In Design view the PHP lines show as small PHP icons. Leave them in place.
* **Images:** insert them as usual (*Insert → Image*), stored under `/images/…`. They resize
  automatically on phones. If an image is not a link, visitors can click it to enlarge it.
* **PDFs:** links to `.pdf` files get a red "PDF" badge automatically.
* **Layouts that fill the space:** pages use a few ready-made layout classes. Copy an existing
  example and change its pictures and text:
  - `<div class="row split">` = a picture (`<div class="block media">`) beside its text
    (`<div class="block">`). Add `reverse` (`class="row split reverse"`) to put the picture on the right.
  - `<div class="row gallery">` = several pictures in one row, same height, filling the width.
  - `<div class="row gallery captioned tiles">` = uniform tiles of logo/picture + caption.
  - `<div class="row gallery captioned promo">` = poster cards with a title/link (home page).
  - `<div class="row stack">` = one picture with its caption centred underneath.
  - `<div class="row keyval">` = a short label (date, time, name) on the left, its details on the right.
  - `<dl class="specs"><dt>Label</dt><dd>Value</dd>…</dl>` = a two-column specification list
    (price, duration, capacity…).
  - `<ol class="notes">` = numbered notes (i), (ii), (iii)…
  - `<ul class="doc-grid"><li><a href="/docs/…pdf">Title</a></li>…</ul>` = document library cards (PDFs).
  - `<div class="link-row"><p><a …>…</a></p>…</div>` = links shown as buttons side by side.
  - `<ul class="link-list">` = a list of longer links (news, headlines) with arrows.
  - `<ul class="fields">` = the details a visitor should include in an e-mail (shown as chips).
  - `<ul class="bullets">` = a long list that flows into columns on wide screens.
  - `<p class="lead">` = a short line that introduces the list or table after it.
  - Real data tables: `<div class="table-wrap"><table>…</table></div>` (a header row uses `<th>`).
  Pictures are never enlarged beyond their real size, so use images that are large enough.
* **Side by side:** put items in `<div class="blocks"><div class="row"><div class="block">…</div><div class="block">…</div></div></div>`.
  The blocks sit next to each other on wide screens and stack on phones.
* **Tables of data** (for example a programme): wrap them in `<div class="table-wrap">…</div>` so
  they scroll on phones.
* Links to other pages: use `/en/…php`, `/el/…php` or `/ru/…php` (the same language as the page).

### Translating a page
The Greek and Russian pages whose text is still in the original language have
`'translated' => false` (they show a short notice). To translate one:
1. open the file in `el/` or `ru/`, replace the text between the CONTENT markers,
2. translate the `title`,
3. set `'translated' => true`.

## 4. Add a new page

1. Copy a similar page, for example `en/program-events/school-visits.php`, to its new name, such as
   `en/program-events/new-course.php`.
2. Change `'path'` to the new address without `.php` (`program-events/new-course`), then change the
   title, the description and the content.
3. Do the same in `el/` and `ru/` (copy the English page and set `'translated' => false` until
   it is translated).
4. **Rebuild the page list, search and sitemap:** start the preview server and open
   `http://localhost:8000/tools/rebuild-index.php`, or run `php tools/rebuild-index.php`.
   The new page then appears automatically in breadcrumbs, the "In this section" cards of its
   parent page, the site map and the search.
5. To show it in the **main menu**, add its path to `$MENU` in `includes/config.php` and its
   name to `$NAV_LABELS` in each `includes/lang/*.php`.

Run the rebuild tool (step 4) after any change to titles or text, so the search stays current.

## 5. Preview on your computer

Install PHP 7.4 or newer, open a terminal in this folder and run:

```
PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php
```

(On Windows, leave out `PHP_CLI_SERVER_WORKERS=8 `. It lets the preview serve several files at once, so
videos don't hold up other pages. Real hosting always does this.)

Then open **http://localhost:8000**. Dreamweaver's Live View can use this address.

## 6. Publish

Check `SITE_URL` in `includes/config.php` (the live address, used for search engines and
social media), then upload everything to an **Apache** host with PHP 7.4 or newer (most shared hosting). The
`.htaccess` file then:
* protects `includes/`, `tools/` and `data/*.php` from the public,
* sends old addresses (for example `/about-us/history.html` or `/pages/historyen.htm`) to the new
  pages with permanent (301) redirects,
* adds security and caching headers.

After installing an SSL certificate, uncomment the two **HTTPS** lines and the
**Strict-Transport-Security** line in `.htaccess`.

## 7. The copy on GitHub Pages

https://samuelakosaonyejekwe.github.io/astronomycypruseu/ is a static copy of this site (branch
`gh-pages`), for previewing without PHP hosting. It is regenerated from the PHP site with
`tools/export-static.py` (see README.md). Search engines are told not to list it.

## 8. Change colours or the menu

* Colours: the variables at the top of `assets/css/site.css` (`--accent` is the gold, `--brand`
  is the original navy). The light-theme colours are in the `[data-theme="light"]` block.
* Menu order and items: `$MENU` in `includes/config.php`.
* Menu names, buttons and footer text: `includes/lang/en.php`, `el.php` and `ru.php`.
