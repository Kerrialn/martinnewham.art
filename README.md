# artstudioprague.com

Static site for martinnewham.art, built with [Stenope](https://stenope.github.io/) —
a static site generator running on Symfony. The Twig templates and controllers are
rendered to plain HTML at build time; there is no database, admin, or runtime server.

## Structure

- `templates/` — Twig templates (`base.html.twig`, `app/index|about|purchase`)
- `src/Controller/Controller/AppController.php` — the three pages. The gallery is built
  from the images in `public/images/` (no database).
- `public/` — static assets (`css/`, `images/`). Copied into the build as-is.
- `build/` — generated static site output (git-ignored).

## Adding artwork to the gallery

Drop a `.jpg` into `public/images/`. The homepage gallery lists every image in that
folder except the exhibition photos used in the carousel (see `AppController::CAROUSEL_IMAGES`).
The card title is derived from the file name.

## Build

```bash
composer install
APP_ENV=prod php bin/console stenope:build
```

The static site is generated in `build/`. Deploy that folder to any static host
(Netlify, GitHub Pages, S3, Caddy/nginx, …).

Preview it locally:

```bash
php -S 127.0.0.1:8000 -t build
```

## Local development

Run the Symfony app directly to preview template changes with live reload:

```bash
php -S 127.0.0.1:8000 -t public
```
