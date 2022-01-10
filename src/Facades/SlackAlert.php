<?php

namespace Spatie\SlackAlerts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\SlackAlerts\SlackAlert
 */
class SlackAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-slack-alerts';
    }
}
