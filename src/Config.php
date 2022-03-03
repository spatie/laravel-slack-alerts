<?php

namespace Spatie\SlackAlerts;

use Spatie\SlackAlerts\Exceptions\JobClassDoesNotExist;
use Spatie\SlackAlerts\Exceptions\WebhookUrlNotValid;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

class Config
{
    public static function getJob(array $arguments): SendToSlackChannelJob
    {
        $jobClass = config('slack-alerts.job');

        if (is_null($jobClass) || ! class_exists($jobClass)) {
            throw JobClassDoesNotExist::make($jobClass);
        }

        return app($jobClass, $arguments);
    }

    public static function getWebhookUrl(string $name): string|null
    {
        if (filter_var($name, FILTER_VALIDATE_URL)) {
            return $name;
        }

        $url = config("slack-alerts.webhook_urls.{$name}");

        if (is_null($url)) {
            return null;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw WebhookUrlNotValid::make($name, $url);
        }

        return $url;
    }
}
