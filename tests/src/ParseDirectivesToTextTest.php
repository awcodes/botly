<?php

declare(strict_types=1);

use Awcodes\Botly\Action\ParseDirectivesToText;
use Awcodes\Botly\Models\Botly;

it('returns an empty string when there is no data', function (): void {
    $result = (new ParseDirectivesToText())->handle();

    expect($result)->toBe('');
});

it('renders user-agent and directives from rules', function (): void {
    Botly::factory()->create([
        'rules' => [
            ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/'],
        ],
        'sitemaps' => [],
        'ai_crawlers' => [],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    expect($result)
        ->toContain('User-agent: *')
        ->toContain('Disallow: /');
});

it('groups multiple rules under the same user-agent', function (): void {
    Botly::factory()->create([
        'rules' => [
            ['user_agent' => 'Googlebot', 'directive' => 'Allow', 'path' => '/public'],
            ['user_agent' => 'Googlebot', 'directive' => 'Disallow', 'path' => '/private'],
        ],
        'sitemaps' => [],
        'ai_crawlers' => [],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    $lines = explode("\n", $result);
    $agentLines = array_filter($lines, fn ($l) => str_contains($l, 'User-agent: Googlebot'));

    expect(count($agentLines))->toBe(1)
        ->and($result)->toContain('Allow: /public')
        ->and($result)->toContain('Disallow: /private');
});

it('renders separate user-agent blocks for different agents', function (): void {
    Botly::factory()->create([
        'rules' => [
            ['user_agent' => 'Googlebot', 'directive' => 'Disallow', 'path' => '/secret'],
            ['user_agent' => 'Bingbot', 'directive' => 'Allow', 'path' => '/open'],
        ],
        'sitemaps' => [],
        'ai_crawlers' => [],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    expect($result)
        ->toContain('User-agent: Googlebot')
        ->toContain('User-agent: Bingbot');
});

it('renders disallow entries for selected ai crawlers', function (): void {
    Botly::factory()->create([
        'rules' => [],
        'sitemaps' => [],
        'ai_crawlers' => ['GPTBot', 'ClaudeBot'],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    expect($result)
        ->toContain('User-agent: GPTBot')
        ->toContain('User-agent: ClaudeBot')
        ->toContain('Disallow: /');
});

it('renders sitemap lines', function (): void {
    Botly::factory()->create([
        'rules' => [],
        'sitemaps' => [
            'https://example.com/sitemap.xml',
            'https://example.com/sitemap-news.xml',
        ],
        'ai_crawlers' => [],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    expect($result)
        ->toContain('Sitemap: https://example.com/sitemap.xml')
        ->toContain('Sitemap: https://example.com/sitemap-news.xml');
});

it('merges persistent rules before db rules', function (): void {
    config()->set('botly.persistent_rules', [
        ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/admin'],
    ]);

    Botly::factory()->create([
        'rules' => [
            ['user_agent' => 'Googlebot', 'directive' => 'Allow', 'path' => '/'],
        ],
        'sitemaps' => [],
        'ai_crawlers' => [],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    $adminPos = mb_strpos($result, 'Disallow: /admin');
    $googlebotPos = mb_strpos($result, 'User-agent: Googlebot');

    expect($adminPos)->toBeLessThan($googlebotPos);
});

it('falls back to botly config defaults when there is no db record', function (): void {
    config()->set('botly.defaults.rules', [
        ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/fallback'],
    ]);

    $result = (new ParseDirectivesToText())->handle();

    expect($result)->toContain('Disallow: /fallback');
});

it('uses factory defaults for a complete robots.txt output', function (): void {
    Botly::factory()->create();

    $result = (new ParseDirectivesToText())->handle();

    expect($result)
        ->toContain('User-agent: GoogleBot')
        ->toContain('User-agent: LinkedInBot')
        ->toContain('Disallow: /')
        ->toContain('Sitemap: https://example.com/sitemap.xml');
});
