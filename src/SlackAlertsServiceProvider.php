<?php

namespace Spatie\SlackAlerts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SlackAlertsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-slack-alerts')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-slack-alerts', function () {
            return new SlackAlert();
        });
    }
}
