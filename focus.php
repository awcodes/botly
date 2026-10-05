<?php

declare(strict_types=1);

use Awcodes\Focus\Card;
use Awcodes\Focus\Enums\Size;
use Awcodes\Focus\Enums\Theme;
use Awcodes\Focus\Screenshot;
use Awcodes\Focus\ScreenshotSuite;

/*
 * Documentation screenshots for Botly, generated with awcodes/focus from the Workbench (run `composer build`
 * first). The Workbench seeds one fixed robots.txt configuration and adds a persistent rule on the panel, so the
 * page and the served /robots.txt are the same on every build. No capture saves the form.
 */

// The awcodes card templates frame each screenshot at 1400x816, so the card source is captured at that size.
$cardSlot = [1400, 816];

// The page's fields sit 24px apart, so a tighter crop than the default keeps the neighbouring field's label out.
$fieldPadding = 16;

return ScreenshotSuite::make()
    ->screenshots([
        Screenshot::make('robots-manager')
            ->visit('/admin/botly')
            ->fullPage(),

        Screenshot::make('rules')
            ->visit('/admin/botly')
            ->focus('[data-focus="botly-rules"]')
            ->padding($fieldPadding),

        Screenshot::make('persistent-rules')
            ->visit('/admin/botly')
            ->focus('[data-focus="botly-persistent-rules"]')
            ->padding($fieldPadding),

        Screenshot::make('sitemaps')
            ->visit('/admin/botly')
            ->focus('[data-focus="botly-sitemaps"]')
            ->padding($fieldPadding),

        Screenshot::make('ai-crawlers')
            ->visit('/admin/botly')
            ->focus('[data-focus="botly-ai-crawlers"]')
            ->padding($fieldPadding),

        // The served file is plain text, which the browser always renders light, so a dark capture would be a
        // duplicate.
        Screenshot::make('robots-txt')
            ->viewportSize(480, 560)
            ->visit('/robots.txt')
            ->focus('pre')
            ->themes([Theme::Light]),

        // The share-image source. The two-up templates show it dark in slot 1 and light in slot 2, so it is
        // captured in both themes.
        Screenshot::make('card-robots-manager')
            ->viewportSize(...$cardSlot)
            ->visit('/admin/botly')
            ->viewport(),
    ])
    ->cardTemplates('https://github.com/awcodes/focus-templates/tree/v2.1.0/dist')
    ->cards([
        // Open Graph and the GitHub social preview share one 2400x1260 template; GitHub crops 30px top and bottom.
        Card::make('social')
            ->template('two-up-wide')
            ->title('Botly')
            ->screenshots(['card-robots-manager', 'card-robots-manager'])
            ->sizes([Size::OpenGraph, Size::GitHubSocial]),

        // The Filament plugin directory's 2560x1440 thumbnail.
        Card::make('thumbnail')
            ->template('two-up')
            ->title('Botly')
            ->screenshots(['card-robots-manager', 'card-robots-manager'])
            ->sizes([Size::Filament]),

        // Unbranded 16:9 image for aw.codes, which adds its own heading: the same screenshots, no text or logo.
        Card::make('plain')
            ->template('two-up-plain')
            ->screenshots(['card-robots-manager', 'card-robots-manager'])
            ->sizes([[2560, 1440]])
            ->scale(1),
    ]);
