<?php

namespace Spatie\SlackLogger;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\SlackLogger\Commands\SlackLoggerCommand;

class SlackLoggerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-slack-logger')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-slack-logger_table')
            ->hasCommand(SlackLoggerCommand::class);
    }
}
