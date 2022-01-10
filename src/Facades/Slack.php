<?php

namespace Spatie\SlackLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\SlackLogger\Slack
 */
class Slack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-slack-logger';
    }
}
