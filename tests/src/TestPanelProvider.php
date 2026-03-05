<?php

declare(strict_types=1);

namespace Awcodes\Botly\Tests;

use Awcodes\Botly\BotlyPlugin;
use Filament\Panel;
use Filament\PanelProvider;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('test')
            ->plugins([BotlyPlugin::make()]);
    }
}
