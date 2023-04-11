<?php

namespace Spatie\SlackAlerts;

use Illuminate\Support\Arr;
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

        if (! $url) {
            return null;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw WebhookUrlNotValid::make($name, $url);
        }

        return $url;
    }

    public static function getMeta(string $name, ?string $tag = null): array
    {
        $meta = config("slack-alerts.meta.{$name}");

        if($tag !== null) {
            $meta = config("slack-alerts.meta.{$name}:{$tag}", $meta);
        }

        if($meta === null) {
            return [];
        }

        return array_filter(
            [
                'username' => Arr::get($meta, 'username'),
                'icon_url' => Arr::get($meta, 'icon_url'),
                'icon_emoji' => Arr::get($meta, 'icon_emoji'),
            ],
            fn ($value) => $value !== null
        );
    }
}
