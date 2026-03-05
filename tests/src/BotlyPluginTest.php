<?php

declare(strict_types=1);

use Awcodes\Botly\BotlyPlugin;

it('has the correct plugin id', function (): void {
    expect(BotlyPlugin::make()->getId())->toBe('botly');
});

it('returns the default slug', function (): void {
    expect(BotlyPlugin::make()->getSlug())->toBe('botly');
});

it('returns a custom slug when set', function (): void {
    expect(BotlyPlugin::make()->slug('robots')->getSlug())->toBe('robots');
});

it('returns the default navigation label translation key', function (): void {
    expect(BotlyPlugin::make()->getNavigationLabel())->toBeString()->not->toBeEmpty();
});

it('returns a custom navigation label when set', function (): void {
    expect(BotlyPlugin::make()->navigationLabel('SEO')->getNavigationLabel())->toBe('SEO');
});

it('returns the default title translation key', function (): void {
    expect(BotlyPlugin::make()->getTitle())->toBeString()->not->toBeEmpty();
});

it('returns a custom title when set', function (): void {
    expect(BotlyPlugin::make()->title('Robots Manager')->getTitle())->toBe('Robots Manager');
});

it('returns an empty array for persistent rules by default', function (): void {
    expect(BotlyPlugin::make()->getPersistentRules())->toBe([]);
});

it('returns persistent rules set via config', function (): void {
    config()->set('botly.persistent_rules', [
        ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/admin'],
    ]);

    expect(BotlyPlugin::make()->getPersistentRules())->toBe([
        ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/admin'],
    ]);
});

it('returns persistent rules set directly on the plugin', function (): void {
    $rules = [
        ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/secret'],
    ];

    expect(BotlyPlugin::make()->persistentRules($rules)->getPersistentRules())->toBe($rules);
});

it('returns a non-empty list of ai crawlers', function (): void {
    $crawlers = BotlyPlugin::make()->getAICrawlers();

    expect($crawlers)->toBeArray()->not->toBeEmpty();
});

it('includes well-known ai crawlers in the list', function (): void {
    $crawlers = BotlyPlugin::make()->getAICrawlers();

    expect($crawlers)
        ->toContain('GPTBot')
        ->toContain('ClaudeBot')
        ->toContain('Google-Extended');
});

it('returns null navigation group by default', function (): void {
    expect(BotlyPlugin::make()->getNavigationGroup())->toBeNull();
});

it('returns a custom navigation group when set', function (): void {
    expect(BotlyPlugin::make()->navigationGroup('SEO')->getNavigationGroup())->toBe('SEO');
});
