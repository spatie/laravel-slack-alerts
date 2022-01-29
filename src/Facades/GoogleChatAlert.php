<?php

namespace GuyWarner\GoogleChatAlerts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self to(string $text)
 * @method static void message(string $text)
 *
 * @see \GuyWarner\GoogleChatAlerts\GoogleChatAlert
 */
class GoogleChatAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-google-chat-alerts';
    }
}
