<?php

namespace GuyWarner\GoogleChatAlerts\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use GuyWarner\GoogleChatAlerts\GoogleChatAlertsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            GoogleChatAlertsServiceProvider::class,
        ];
    }
}
