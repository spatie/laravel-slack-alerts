<?php

namespace Spatie\SlackLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\SlackLogger\SlackLogger
 */
class SlackLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-slack-logger';
    }
}
