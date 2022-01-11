<?php

namespace Spatie\SlackAlerts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self to(string $text)
 * @method static void message(string $text)
 *
 * @see \Spatie\SlackAlerts\SlackAlert
 */
class SlackAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-slack-alerts';
    }
}
