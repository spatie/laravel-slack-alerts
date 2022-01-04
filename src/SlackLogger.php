<?php

namespace Spatie\SlackLogger;

use Spatie\SlackLogger\Exceptions\InvalidUrl;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;

class SlackLogger
{
    public static function display(string $text): void
    {
        $url = config('slack-logger.webhook_url');

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrl();
        }

        SendToSlackChannelJob::dispatch($text, $url);
    }

    // ideas: to a specific queue or connection, dispatch after response, specify $tries, handle failures
}
