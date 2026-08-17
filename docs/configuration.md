---
title: Configuration
description: Set default values, define rules that cannot be edited, and customize Botly's page and navigation entry.
---

# Configuration

Botly is configured in two places: the published config file, for values that live in your repository, and fluent methods on the plugin, for how the page appears in your panel.

## The config file

Publish the config file with:

```bash
php artisan vendor:publish --tag="botly-config"
```

It contains two keys:

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

### Defaults

The `defaults` array seeds the output before anything has been saved in the panel. It is used only while no Botly record exists in the database — as soon as the Robots Manager page is saved for the first time, the stored values take over and `defaults` is no longer consulted.

Use it to give a fresh install sensible output, not as a way to enforce rules. For rules that must always be present, use persistent rules instead.

### Persistent rules

Persistent rules are always included in the output and cannot be edited or deleted from the admin UI. They appear in the rules list on the page with their fields disabled and no delete action.

Define them in the config file:

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

Or fluently on the plugin:

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

Both sources are used — rules from the config file come first, followed by rules passed to `persistentRules()`. Each rule is an array with three keys:

| Key | Values |
|---|---|
| `user_agent` | Any string, for example `*` or `Googlebot` |
| `directive` | `allow`, `disallow`, `crawl-delay` or `clean-param` |
| `path` | The path the directive applies to, for example `/admin` |

Persistent rules are emitted before the rules stored in the database.

## Authorization

By default, any user who can reach your panel can open the Botly page. Restrict it by passing a boolean or a closure to `authorize()`:

```php
// Always deny access
BotlyPlugin::make()
    ->authorize(false),

// Conditionally allow access
BotlyPlugin::make()
    ->authorize(fn (): bool => auth()->user()->isAdmin()),
```

Access is granted only when the value resolves to exactly `true`.

## Navigation

Customize how the page appears in your panel's navigation:

```php
BotlyPlugin::make()
    ->navigationIcon('heroicon-o-document-text')
    ->navigationGroup('Settings')
    ->navigationLabel('Robots.txt')
    ->navigationSort(3),
```

If you do not set an icon, Botly uses its own built-in robot icon. The navigation label defaults to **Robots Manager**.

## Page title and slug

```php
BotlyPlugin::make()
    ->title('Robots Manager')
    ->slug('robots-manager'),
```

The title defaults to **Manage robots.txt** and the slug defaults to `botly`, so the page lives at `/{panel-path}/botly` unless you change it.
