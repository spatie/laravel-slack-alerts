<?php

namespace Spatie\SlackLogger;

use Spatie\SlackLogger\Exceptions\JobClassDoesNotExist;
use Spatie\SlackLogger\Exceptions\InvalidUrl;
use Spatie\SlackLogger\Exceptions\WebhookDoesNotExist;
use Spatie\SlackLogger\Exceptions\WebhookUrlNotValid;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;

class Config
{
    public static function getJob(array $arguments): SendToSlackChannelJob
    {
        $jobClass = config('slack-logger.job');

        if (! class_exists($jobClass)) {
            throw JobClassDoesNotExist::make($jobClass);
        }

        return app($jobClass, $arguments);
    }

    public static function getWebhookUrl(string $name): string
    {
        $url = config("slack-logger.webhook_urls.{$name}");

        if (is_null($url)) {
            throw WebhookDoesNotExist::make($name);
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw WebhookUrlNotValid::make($name, $url);
        }

        return $url;
    }
}
