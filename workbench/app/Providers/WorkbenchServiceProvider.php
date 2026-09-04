<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Workbench\App\Console\Commands\AssertBotlyMigration;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            AssertBotlyMigration::class,
        ]);
    }
}
