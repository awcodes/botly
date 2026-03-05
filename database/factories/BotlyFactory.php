<?php

declare(strict_types=1);

namespace Awcodes\Botly\Database\Factories;

use Awcodes\Botly\Models\Botly;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of Botly
 *
 * @extends Factory<TModel>
 */
class BotlyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Botly::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rules' => [
                [
                    'user_agent' => 'GoogleBot',
                    'directive' => 'disallow',
                    'path' => '/public',
                ],
                [
                    'user_agent' => 'BingBot',
                    'directive' => 'allow',
                    'path' => '/open',
                ],
            ],
            'sitemaps' => [
                'https://example.com/sitemap.xml',
            ],
            'ai_crawlers' => [
                'LinkedInBot',
            ],
        ];
    }
}
