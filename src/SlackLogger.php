<?php

namespace Spatie\SlackLogger;

class SlackLogger
{
    public static function display(string $text): void
    {
        dispatch(new SendToSlackChannelJob($text));
    }
}
