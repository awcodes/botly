<?php

declare(strict_types=1);

use Awcodes\Botly\Models\Botly;

it('serves robots.txt at the correct url', function (): void {
    $response = $this->get('/robots.txt');

    $response->assertStatus(200);
});

it('returns a text/plain content type', function (): void {
    $response = $this->get('/robots.txt');

    $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
});

it('returns robots.txt content from the database', function (): void {
    Botly::factory()->create([
        'rules' => [
            ['user_agent' => '*', 'directive' => 'Disallow', 'path' => '/private'],
        ],
        'sitemaps' => ['https://example.com/sitemap.xml'],
        'ai_crawlers' => [],
    ]);

    $response = $this->get('/robots.txt');

    $response->assertStatus(200)
        ->assertSee('User-agent: *')
        ->assertSee('Disallow: /private')
        ->assertSee('Sitemap: https://example.com/sitemap.xml');
});

it('returns an empty body when there is no data', function (): void {
    $response = $this->get('/robots.txt');

    $response->assertStatus(200);
    expect(mb_trim($response->content()))->toBe('');
});
