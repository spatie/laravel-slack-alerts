<?php

namespace Spatie\SlackAlerts\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\SlackAlerts\FakeSlackAlert;

/**
 * @method static self to(string $text)
 * @method static self toChannel(string $text)
 * @method static void message(string $text)
 * @method static void blocks(array $blocks)
 * @method static self withUsername(string $text)
 * @method static self withIconURL(string $text)
 * @method static self withIconEmoji(string $text)
 * @method static self onQueue(string $text)
 *
 * @see \Spatie\SlackAlerts\SlackAlert
 */
class SlackAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-slack-alerts';
    }

    public static function fake()
    {
        static::swap(new FakeSlackAlert());

        return new static;
    }
}
