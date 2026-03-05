<?php

declare(strict_types=1);

namespace Awcodes\Botly;

use BackedEnum;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use UnitEnum;

class BotlyPlugin implements Plugin
{
    use EvaluatesClosures;

    protected string | BackedEnum | null $navigationIcon = null;

    protected string | UnitEnum | null $navigationGroup = null;

    protected string | Closure | null $navigationLabel = null;

    protected string | Closure | null $title = null;

    protected string | Closure | null $slug = null;

    protected ?array $persistentRules = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'botly';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                Filament\Pages\BotlyPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function navigationIcon(string | BackedEnum | Htmlable | null $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationGroup(string | BackedEnum | null $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationLabel(string | Closure | null $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function title(string | Closure | null $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function slug(string | Closure | null $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function persistentRules(array $rules): static
    {
        $this->persistentRules = $rules;

        return $this;
    }

    public function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return $this->navigationIcon ?? new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"> <path d="M6 4m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /> <path d="M12 2v2" /> <path d="M9 12v9" /> <path d="M15 12v9" /> <path d="M5 16l4 -2" /> <path d="M15 14l4 2" /> <path d="M9 18h6" /> <path d="M10 8v.01" /> <path d="M14 8v.01" /></svg>');
    }

    public function getNavigationGroup(): string | BackedEnum | null
    {
        return $this->navigationGroup;
    }

    public function getNavigationLabel(): string
    {
        return $this->evaluate($this->navigationLabel) ?? __('botly::botly.navigation.label');
    }

    public function getTitle(): ?string
    {
        return $this->evaluate($this->title) ?? __('botly::botly.title');
    }

    public function getSlug(): ?string
    {
        return $this->evaluate($this->slug) ?? 'botly';
    }

    public function getPersistentRules(): array
    {
        return $this->persistentRules ?? config('botly.persistent_rules', []);
    }

    /**
     * Reference list of common AI crawlers
     * https://momenticmarketing.com/blog/ai-search-crawlers-bots
     */
    public function getAICrawlers(): array
    {
        return [
            'OAI-SearchBot',
            'ChatGPT-User',
            'ChatGPT-User/2.0',
            'GPTBot',
            'anthropic-ai',
            'ClaudeBot',
            'claude-web',
            'PerplexityBot',
            'Perplexity-User',
            'Google-Extended',
            'BingBot',
            'Amazonbot',
            'Applebot',
            'Applebot-Extended',
            'FacebookBot',
            'meta-externalagent',
            'LinkedInBot',
            'Bytespider',
            'DuckAssistBot',
            'cohere-ai',
            'AI2Bot',
            'CCBot',
            'Diffbot',
            'omgili',
        ];
    }
}
