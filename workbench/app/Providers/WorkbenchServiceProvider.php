<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Support\ServiceProvider;
use Workbench\App\Console\Commands\AssertBotlyMigration;
use Workbench\App\Console\Commands\RemoveStaticRobotsFile;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            AssertBotlyMigration::class,
            RemoveStaticRobotsFile::class,
        ]);
    }

    public function boot(): void
    {
        // Hooks for the documentation screenshots in focus.php, added here so Botly's page stays unchanged.
        Repeater::configureUsing(fn (Repeater $component): Repeater => $component
            ->extraFieldWrapperAttributes(['data-focus' => $this->focusHook($component->getName())]));

        CheckboxList::configureUsing(fn (CheckboxList $component): CheckboxList => $component
            ->extraFieldWrapperAttributes(['data-focus' => $this->focusHook($component->getName())]));

        RepeatableEntry::configureUsing(fn (RepeatableEntry $component): RepeatableEntry => $component
            ->extraEntryWrapperAttributes(['data-focus' => $this->focusHook($component->getName())]));
    }

    private function focusHook(string $name): string
    {
        return 'botly-' . str_replace('_', '-', $name);
    }
}
