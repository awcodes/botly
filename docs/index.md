---
title: Botly
description: Manage your site's robots.txt file from the Filament admin panel, stored in the database and served dynamically.
---

# Botly

Botly is a Filament plugin for managing your site's `robots.txt` file from your admin panel. Rules, sitemaps and AI crawler blocks are stored in the database and served dynamically, so you do not need to keep a static file in your repository or redeploy to change what crawlers are told.

It is for sites where the people who decide crawler policy are not the people who deploy the code — a marketing team that needs to block a bot today, or a site where `robots.txt` changes often enough that editing a file in version control is friction.

## How it works

Botly registers a **Robots Manager** page in your panel and a route that answers `/robots.txt`.

When a crawler requests `/robots.txt`, Botly reads the current configuration from the database, merges in any persistent rules you have defined in code, and formats the result as valid `robots.txt` output on the fly. Nothing is written to disk unless you ask for it.

The output is assembled in a fixed order:

1. Rules, grouped by user agent — persistent rules first, then the rules stored in the database.
2. Blocked AI crawlers, each as a `User-agent` line followed by `Disallow: /`.
3. Sitemap URLs, as `Sitemap:` lines.

If you would rather serve a static file, the admin page has an **Export Robots.txt** action that writes the current output to `public/robots.txt`.

> [!IMPORTANT]
> A static `public/robots.txt` file takes precedence over Botly's route, because the web server serves it before the request ever reaches Laravel. If one exists, Botly shows a warning on the admin page with actions to delete or rename it.

## Requirements

Botly requires PHP 8.2 or higher and Filament v4 or v5.

## Where to go next

- [Installation](installation.md) — install the package, run the migration and register the plugin.
- [Usage](usage.md) — manage rules, sitemaps and AI crawler blocks from the admin page.
- [Configuration](configuration.md) — set defaults, define rules that cannot be edited, and customize the page.
