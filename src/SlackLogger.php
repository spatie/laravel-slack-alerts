<?php

namespace Spatie\SlackLogger;

use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;

class SlackLogger
{
    public static function display(string $text): void
    {
        dispatch(new SendToSlackChannelJob($text));
    }
}
