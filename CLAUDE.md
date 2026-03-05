# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This Is

Botly is a Filament plugin (Laravel package) that manages a site's `robots.txt` file from the Filament admin panel. It stores robots directives in a database and serves them dynamically via a route or exports them to a static file.

## Commands

```bash
# Run all checks (refactor dry-run, lint check, types, unit tests)
composer test

# Individual checks
composer lint              # Fix code style with Pint
composer test:lint         # Check code style without fixing
composer refactor          # Apply Rector transformations
composer test:refactor     # Dry-run Rector
composer test:types        # Run PHPStan (level 5)
composer test:unit         # Run Pest tests

# Run a single test file
./vendor/bin/pest tests/src/DebugTest.php

# JS assets
npm run build              # Build assets via esbuild
npm run dev                # Build in watch mode
```

## Architecture

This is a **Laravel package** using `spatie/laravel-package-tools`. The package registers itself via `BotlyServiceProvider` and exposes a Filament plugin via `BotlyPlugin`.

**Data flow:**
1. `BotlyPage` (Filament page) — admin UI with a form for managing rules, sitemaps, and AI crawler blocks. Saves to the `botly` DB table via the `Botly` model.
2. `ParseDirectivesToText` (action) — reads from the DB, merges persistent rules, formats everything as `robots.txt` text.
3. `BotlyController` — serves `/robots.txt` dynamically using `ParseDirectivesToText`.
4. Export action on `BotlyPage` — writes the output of `ParseDirectivesToText` to `public/robots.txt` as a static file.

**Key concepts:**
- **Persistent rules** — rules set via `BotlyPlugin::make()->persistentRules([...])` that cannot be edited or deleted from the UI. Merged at the top of the output.
- **AI crawlers** — a checkbox list of known AI bots (defined in `BotlyPlugin::getAICrawlers()`). Checked bots get `Disallow: /` entries.
- The `Botly` model always operates on a single row (`id = 1`, via `updateOrCreate`).
- If a static `public/robots.txt` exists, `BotlyPage` shows a warning callout with options to delete or rename the file.

**Plugin registration** (consumer usage):
```php
$panel->plugins([BotlyPlugin::make()]);
```

**Testing:** Uses Orchestra Testbench with `WithWorkbench`. Tests live in `tests/src/`, test migrations in `tests/database/migrations/`, and the workbench app is in `workbench/`.

**Code style:** All files use `declare(strict_types=1)`. Pint for formatting, Rector for refactoring, PHPStan at level 5 for static analysis.
