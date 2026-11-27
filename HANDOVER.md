# Handing over the Kition Planetarium & Observatory website

This folder is the complete website. Whoever receives it can open, change, add or remove anything
in Adobe Dreamweaver. It is plain PHP + HTML + CSS, with no database and no build step.

## What is in the package

| Item | Purpose |
|---|---|
| `en/`, `el/`, `ru/` | Every page in English, Greek and Russian, one `.php` file per page |
| `includes/` | Shared header, footer and menu, plus the interface text of each language (`includes/lang/`) |
| `assets/` | Styles (`css/site.css`) and scripts (`js/site.js`) |
| `images/`, `docs/`, `video/` | Pictures, PDF documents and videos |
| `data/` | Page list, search index and old-address redirects (generated) |
| `tools/` | `rebuild-index.php` (search, page list, sitemap) and `export-static.py` (GitHub Pages copy) |
| `DREAMWEAVER-GUIDE.md` | How to edit pages, add pages, change the menu, colours and layouts |
| `README.md` | Short overview and commands |

## 1. Set up a computer (Windows, one time)

1. Install **XAMPP** (free, from apachefriends.org). It includes PHP and Apache, the same software
   as typical web hosting.
2. Copy the contents of this folder into `C:\xampp\htdocs\`, replacing XAMPP's sample files.
   The site uses addresses that start at the site root (`/en/…`, `/images/…`), so it must be the
   root of the local server.
3. Open the **XAMPP Control Panel** and click **Start** next to **Apache**.
4. In a browser, open **http://localhost/**. The site should appear in the visitor's language.

On a Mac or Linux you can skip XAMPP: open a terminal in the folder and run
`PHP_CLI_SERVER_WORKERS=8 php -S localhost:8000 router.php`, then open http://localhost:8000/.

## 2. Set up Dreamweaver (one time)

1. **Site → New Site…**, Local Site Folder: `C:\xampp\htdocs\`.
2. **Servers → +**: *Server Name* Local, *Connect using* Local/Network, *Server Folder*
   `C:\xampp\htdocs\`, *Web URL* `http://localhost/`. Under **Advanced**, choose the **PHP MySQL**
   server model (MySQL is not used). Tick **Testing**.
3. Add the live hosting (FTP/SFTP details from the hosting company) as a second server and tick **Remote**.
4. **Advanced Settings → Local Info**: *Links relative to* **Site Root**.
5. Open any page and use **Live View** to see it exactly as visitors will.

## 3. Everyday work

* **Change text or pictures:** open the page (e.g. `en/about-us/history.php`) and edit between
  `CONTENT START` and `CONTENT END`. Do the same in `el/` and `ru/` for the other languages.
* **Add a page:** copy a similar page, change `path`, `title` and the content (see DREAMWEAVER-GUIDE.md §4).
* **Remove a page:** delete its file in `en/`, `el/` and `ru/`, and remove any menu entry for it
  (`includes/config.php`).
* **Change the menu:** `includes/config.php` (`$MENU`), names in `includes/lang/*.php`.
* **Change colours/fonts:** variables at the top of `assets/css/site.css`.
* **After adding, removing or renaming pages, or editing titles/text:** open
  **http://localhost/tools/rebuild-index.php** (or run `php tools/rebuild-index.php`). This refreshes
  the search, the "In this section" lists, the site map, `sitemap.xml` and `robots.txt`.

## 4. Publish

1. Make sure `SITE_URL` in `includes/config.php` is the live address (e.g. `https://astronomycyprus.eu`).
2. Upload everything to the hosting with Dreamweaver (**Put**) or any FTP program. The hosting needs
   **PHP 7.4 or newer** on **Apache** (most shared hosting).
3. After SSL is active, uncomment the HTTPS and Strict-Transport-Security lines in `.htaccess`.

## 5. Optional: the copy on GitHub Pages

The GitHub repository has a static copy for previewing without PHP hosting (branch `gh-pages`).
To refresh it after editing, follow the steps in README.md. It needs Python 3 and Git, and it is
not required for running the real site.

## 6. Good to know

* Old addresses of the previous website (`/about-us/history.html`, `/pages/historyen.htm`…) are
  automatically sent to the new pages. To redirect another old address, add a line to `data/redirects.php`.
* Keep a backup copy of the folder before large changes (or use Git, which records every change).
