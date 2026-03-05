# Botly

Botly is a Filament plugin to manage your site's `robots.txt` file directly from the Filament admin panel. Rules, sitemaps, and AI crawler blocks are stored in the database and served dynamically — no static file required.

[![Latest Version](https://img.shields.io/github/release/awcodes/botly.svg?style=flat-square&color=blue&label=Release)](https://github.com/awcodes/botly/releases)
[![MIT Licensed](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/awcodes/botly.svg?style=flat-square&color=blue&label=Downloads)](https://packagist.org/packages/awcodes/botly)
[![GitHub Repo stars](https://img.shields.io/github/stars/awcodes/botly?style=flat-square&color=blue&label=Stars)](https://github.com/awcodes/botly/stargazers)
[![Filament Version](https://img.shields.io/badge/Filament-4.x-d97706.svg?style=flat-square)](https://filamentphp.com/docs/4.x/panels/installation)
[![Filament Version](https://img.shields.io/badge/Filament-5.x-d97706.svg?style=flat-square)](https://filamentphp.com/docs/5.x/panels/installation)

## Installation

Install the package via Composer:

```bash
composer require awcodes/botly
```

Run the installation command to publish migrations and run them:

```bash
php artisan botly:install
```

Or publish and run the migration manually:

```bash
php artisan vendor:publish --tag="botly-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="botly-config"
```

## Setup

Register the plugin in your Filament panel provider:

```php
use Awcodes\Botly\BotlyPlugin;

$panel->plugins([
    BotlyPlugin::make(),
]);
```

That's it. Botly registers a **Robots Manager** page in your panel and automatically serves `/robots.txt` via a dynamic route.

## How It Works

Botly stores your robots configuration in the database. When `/robots.txt` is requested, the rules are read from the database and formatted as valid `robots.txt` output on the fly. You can also export the current configuration to a static `public/robots.txt` file using the **Export Robots.txt** button on the admin page.

> [!IMPORTANT]
> If a static `public/robots.txt` file already exists, Botly will display a warning in the admin UI. The file must be deleted or renamed before the dynamic route can take effect.

## Configuration

The published config file (`config/botly.php`) allows you to set default values that are used when no database record exists yet:

```php
return [
    'defaults' => [
        'rules' => [],
        'sitemaps' => [],
        'ai_crawlers' => [],
    ],
    'persistent_rules' => [],
];
```

### Persistent Rules

Persistent rules are rules that are always included in the output and cannot be edited or deleted from the admin UI. You can define them in the config file or fluently on the plugin:

**Via config:**

```php
// config/botly.php
'persistent_rules' => [
    [
        'user_agent' => '*',
        'directive' => 'disallow',
        'path' => '/admin',
    ],
],
```

**Via plugin:**

```php
BotlyPlugin::make()
    ->persistentRules([
        [
            'user_agent' => '*',
            'directive' => 'disallow',
            'path' => '/admin',
        ],
    ]),
```

Each rule is an array with three keys:

| Key          | Values                                            |
|--------------|---------------------------------------------------|
| `user_agent` | Any string, e.g. `*`, `Googlebot`                 |
| `directive`  | `allow`, `disallow`, `crawl-delay`, `clean-param` |
| `path`       | The path to allow or disallow, e.g. `/admin`      |

## Customisation

### Navigation

```php
BotlyPlugin::make()
    ->navigationIcon('heroicon-o-robot')
    ->navigationGroup('Settings')
    ->navigationLabel('Robots.txt'),
```

### Page

```php
BotlyPlugin::make()
    ->title('Robots Manager')
    ->slug('robots-manager'),
```

## AI Crawler Blocking

The admin page includes a **Block AI Crawlers** checkbox list. Selecting crawlers will add `Disallow: /` entries for each one in the output. Botly ships with a curated list of known AI crawlers including GPTBot, ClaudeBot, PerplexityBot, and more.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Adam Weston](https://github.com/awcodes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
