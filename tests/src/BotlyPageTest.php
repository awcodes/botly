<?php

declare(strict_types=1);

use Awcodes\Botly\BotlyPlugin;
use Awcodes\Botly\Filament\Pages\BotlyPage;
use Filament\Facades\Filament;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('test'));
});

it('allows access to the page by default', function (): void {
    expect(BotlyPage::canAccess())->toBeTrue();
});

it('denies access to the page when authorize is set to false', function (): void {
    BotlyPlugin::get()->authorize(false);

    expect(BotlyPage::canAccess())->toBeFalse();
});

it('denies access to the page when the authorize closure returns false', function (): void {
    BotlyPlugin::get()->authorize(fn () => false);

    expect(BotlyPage::canAccess())->toBeFalse();
});

it('allows access to the page when the authorize closure returns true', function (): void {
    BotlyPlugin::get()->authorize(fn () => true);

    expect(BotlyPage::canAccess())->toBeTrue();
});
