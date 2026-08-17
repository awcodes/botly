---
title: Installation
description: Install Botly, run its migration and register the plugin in a Filament panel.
---

# Installation

## Requirements

Botly requires PHP 8.2 or higher and Filament v4 or v5.

## Install the package

Install Botly via Composer:

```bash
composer require awcodes/botly
```

## Run the migration

Botly stores its configuration in a database table, so it needs a migration. The install command publishes the migration and offers to run it for you:

```bash
php artisan botly:install
```

If you would rather do it yourself, publish the migration and migrate manually:

```bash
php artisan vendor:publish --tag="botly-migrations"
php artisan migrate
```

## Publish the config file

The config file is optional. Publish it if you want to set default values or define persistent rules in code:

```bash
php artisan vendor:publish --tag="botly-config"
```

See [Configuration](configuration.md) for what it contains.

## Register the plugin

Register Botly in your panel provider:

```php
use Awcodes\Botly\BotlyPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            BotlyPlugin::make(),
        ]);
}
```

That is the whole setup. Botly registers a **Robots Manager** page in your panel and a route that answers `/robots.txt`.

## Check for an existing file

If your project already has a `public/robots.txt`, your web server will serve that file directly and Botly's route will never run. Botly detects this and shows a warning on the admin page with actions to delete the file or rename it to `robots-bak.txt`.

> [!TIP]
> Visit `/robots.txt` after installing. If you see your old contents rather than what the admin page shows, a static file is still in place.
