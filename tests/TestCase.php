<?php

namespace Spatie\SlackAlerts\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\SlackAlerts\SlackAlertsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SlackAlertsServiceProvider::class,
        ];
    }
}
