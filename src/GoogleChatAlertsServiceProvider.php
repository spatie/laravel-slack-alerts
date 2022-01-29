<?php

namespace GuyWarner\GoogleChatAlerts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GoogleChatAlertsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-google-chat-alerts')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-google-chat-alerts', function () {
            return new GoogleChatAlert();
        });
    }
}
