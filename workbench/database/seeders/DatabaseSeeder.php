<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Awcodes\Botly\Models\Botly;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Botly::query()->create([
            'rules' => [
                ['user_agent' => '*', 'directive' => 'disallow', 'path' => '/admin'],
                ['user_agent' => 'Googlebot', 'directive' => 'allow', 'path' => '/'],
            ],
            'sitemaps' => ['https://example.com/sitemap.xml'],
            'ai_crawlers' => ['GPTBot'],
        ]);
    }
}
