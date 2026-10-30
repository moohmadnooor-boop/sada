# صدى · Sada

**كلمات يبقى صداها** — منصة عربية لمشاركة الاقتباسات، مبنية بـ Laravel و Blade.
An Arabic quotes platform built with Laravel 13, Blade, Tailwind CSS 4 and SQLite.

**Live:** https://sada-rlwo.onrender.com (free Render instance — the first visit after a quiet period takes about a minute to wake up).

## Features

| Core (from the SRS) | New features |
| --- | --- |
| View all quotes as a guest | Categories + filter |
| Sign up / log in / log out | Original author ("who said it") |
| Share a quote (3–155 chars) | Search and sort (newest / oldest / most liked) |
| Edit / delete own quotes only | Likes and bookmarks |
| Hashed passwords, ownership policies | Comments |
| Field hints + live character counters | Follow users + "Following" feed |
| | Profile pages + edit profile |
| | Quote of the day, weekly trending |
| | Copy / share to X and WhatsApp |
| | Turn a quote into a downloadable image |
| | Night / day theme, animated landing page |
| | Library of classic books, each with its quotes |

## Run it

Requirements: PHP 8.3+, Composer, Node 20+.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Open http://localhost:8000.

The repository already includes a seeded `database/database.sqlite` and a built `public/build`, so after `composer install` and `php artisan key:generate` you can skip straight to `php artisan serve`.

### Demo accounts

The seeder creates five demo users (`sara@sada.test`, `abdullah@sada.test`, `noura@sada.test`, `faisal@sada.test`, `reem@sada.test`), all with the password `password123`.

### Hero video (optional)

Put an `.mp4` at `public/videos/hero.mp4` (and optionally a poster at `public/images/hero.jpg`) and it plays behind the animated echo rings on the home page.

## Image credits

The real book covers in `public/images/books/` come from Wikimedia Commons:

| Book | Source | Licence |
| --- | --- | --- |
| The Prophet | *The Prophet (Gibran).jpg* — 1923 first edition cover | Public domain |
| Diwan al-Mutanabbi | *ديوان المتنبي بشرح الواحدي - 1861.pdf* (title page) | Public domain |
| Al-Shawqiyyat | *الشوقيات - أحمد شوقي ١٩٢٦.png* — Hindawi Foundation | [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/) |
| Muqaddimah | *La Muqaddima d'Ibn Khaldoun.jpg* — Riad Salih | [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/) |
| Masnavi | *Opening page of Masnavi of Rumi, Shiraz, circa 1460, Bodleian Library's Manuscript Elliott 251.jpg* | Public domain |

Diwan al-Shafi'i has no freely licensed cover, so it uses the generated Sada cover.

## Tests

```bash
php -d memory_limit=512M vendor/bin/pest
```

43 Pest feature tests cover every functional requirement (FR-01 – FR-24) and the security rules.

## Structure

- `resources/views/Layout/app.blade.php` — main layout (`@yield('content')`), every page `@extends` it
- `resources/views/Layout/AuthImg.blade.php` — auth layout, itself extends `Layout.app`
- `resources/views/components/` — Blade components: `navbar`, `quote-card`, `quote-grid`, `book-cover`, `field`, `avatar`, `icon`, `share-modal`, `image-modal`, `page-header`, `empty-state`, `pagination`, `logo`, `footer`, `echo-art`
- `app/Policies/` — `QuotePolicy`, `CommentPolicy` (owner-only edit/delete)
- `app/Http/Requests/` — validation with Arabic error messages
- `resources/js/app.js` — likes, bookmarks, follows, copy/share, quote-to-image canvas, theme, animations
