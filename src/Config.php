<?php

namespace GuyWarner\GoogleChatAlerts;

use GuyWarner\GoogleChatAlerts\Exceptions\JobClassDoesNotExist;
use GuyWarner\GoogleChatAlerts\Exceptions\WebhookDoesNotExist;
use GuyWarner\GoogleChatAlerts\Exceptions\WebhookUrlNotValid;
use GuyWarner\GoogleChatAlerts\Jobs\SendToGoogleChatChannelJob;

class Config
{
    public static function getJob(array $arguments): SendToGoogleChatChannelJob
    {
        $jobClass = config('google-chat-alerts.job');

        if (is_null($jobClass) || ! class_exists($jobClass)) {
            throw JobClassDoesNotExist::make($jobClass);
        }

        return app($jobClass, $arguments);
    }

    public static function getWebhookUrl(string $name): string
    {
        $url = config("google-chat-alerts.webhook_urls.{$name}");

        if (is_null($url)) {
            throw WebhookDoesNotExist::make($name);
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw WebhookUrlNotValid::make($name, $url);
        }

        return $url;
    }
}
