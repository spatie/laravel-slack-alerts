<?php

namespace Spatie\SlackLogger;

use Spatie\SlackLogger\Exceptions\InvalidUrl;

class Slack
{
    public static function display(string $text): void
    {
        $webhookUrl = config('slack-logger.webhook_url');

        if (filter_var($webhookUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrl();
        }

        $jobClass = app(config('slack-logger.job'), [
            'text' => $text,
            'webhookUrl' => $webhookUrl,
        ]);

        dispatch($jobClass);
    }

    // ideas: to a specific queue or connection, dispatch after response, specify $tries, handle failures
}
