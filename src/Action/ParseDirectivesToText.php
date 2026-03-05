<?php

declare(strict_types=1);

namespace Awcodes\Botly\Action;

use Awcodes\Botly\BotlyPlugin;
use Awcodes\Botly\Models\Botly;

class ParseDirectivesToText
{
    public function handle(): string
    {
        $lines = [];

        $options = Botly::query()->first();

        $data = [
            'rules' => [
                ...BotlyPlugin::get()->getPersistentRules(),
                ...($options ? $options->toArray()['rules'] : config('botly.defaults.rules', [])),
            ],
            'sitemaps' => ($options ? $options->toArray()['sitemaps'] : config('botly.defaults.sitemaps', [])),
            'ai_crawlers' => ($options ? $options->toArray()['ai_crawlers'] : config('botly.defaults.ai_crawlers', [])),
        ];

        foreach (collect($data['rules'])->groupBy('user_agent')->toArray() as $agent => $rule) {
            $lines[] = "User-agent: {$agent}";

            foreach ($rule as $r) {
                $lines[] = "{$r['directive']}: {$r['path']}";
            }
            $lines[] = '';
        }

        foreach ($data['ai_crawlers'] ?? [] as $crawler) {
            $crawler = BotlyPlugin::get()->getAICrawlers()[$crawler] ?? $crawler;
            $lines[] = "User-agent: {$crawler}";
            $lines[] = 'Disallow: /';
            $lines[] = '';
        }

        foreach ($data['sitemaps'] as $sitemap) {
            $lines[] = "Sitemap: {$sitemap}";
        }

        return implode("\n", $lines);
    }
}
