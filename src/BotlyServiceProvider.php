<?php

declare(strict_types=1);

namespace Awcodes\Botly;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BotlyServiceProvider extends PackageServiceProvider
{
    public static string $name = 'botly';

    public static string $viewNamespace = 'botly';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile('botly')
            ->hasRoute('web')
            ->hasMigration('create_botly_table')
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('awcodes/botly');
            });
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void {}

    protected function getAssetPackageName(): ?string
    {
        return 'awcodes/botly';
    }
}
