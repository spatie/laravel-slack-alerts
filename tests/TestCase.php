<?php

namespace Spatie\SlackLogger\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\SlackLogger\SlackLoggerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SlackLoggerServiceProvider::class,
        ];
    }
}
