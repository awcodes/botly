<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Awcodes\Botly\Models\Botly;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $seededAt = Carbon::parse('2026-01-01 09:00:00');

        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => $seededAt,
            'created_at' => $seededAt,
            'updated_at' => $seededAt,
        ]);

        // A shop's robots.txt, covering every directive Botly supports. The Workbench panel adds a persistent
        // `Disallow: /admin` rule on top of these.
        Botly::query()->create([
            'rules' => [
                ['user_agent' => '*', 'directive' => 'disallow', 'path' => '/cart'],
                ['user_agent' => '*', 'directive' => 'disallow', 'path' => '/checkout'],
                ['user_agent' => '*', 'directive' => 'disallow', 'path' => '/account'],
                ['user_agent' => '*', 'directive' => 'clean-param', 'path' => 'utm_source&utm_medium /products/'],
                ['user_agent' => 'Googlebot', 'directive' => 'allow', 'path' => '/'],
                ['user_agent' => 'Bingbot', 'directive' => 'crawl-delay', 'path' => '5'],
            ],
            'sitemaps' => [
                'https://example.com/sitemap.xml',
                'https://example.com/blog/sitemap.xml',
            ],
            'ai_crawlers' => ['GPTBot', 'ClaudeBot', 'CCBot', 'Bytespider'],
        ]);
    }
}
