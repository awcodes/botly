<?php

declare(strict_types=1);

namespace Awcodes\Botly\Enums;

enum Directive: string
{
    case Allow = 'allow';

    case Disallow = 'disallow';

    case CrawlDelay = 'crawl-delay';

    case CleanParam = 'clean-param';

    /**
     * Rules can be stored in any case, so a key that was saved as `Disallow` still matches.
     */
    public static function tryFromKey(string $key): ?self
    {
        return self::tryFrom(strtolower($key));
    }

    /**
     * The directive as robots.txt spells it. This is fixed, so it is never translated.
     */
    public static function toRobotsName(string $key): string
    {
        return self::tryFromKey($key)?->getRobotsName() ?? $key;
    }

    /**
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $directive): array => [$directive->value => $directive->getLabel()])
            ->all();
    }

    public function getRobotsName(): string
    {
        return match ($this) {
            self::Allow => 'Allow',
            self::Disallow => 'Disallow',
            self::CrawlDelay => 'Crawl-delay',
            self::CleanParam => 'Clean-param',
        };
    }

    public function getLabel(): string
    {
        return __('botly::botly.form.rules.fields.' . str_replace('-', '_', $this->value));
    }
}
