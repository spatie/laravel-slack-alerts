<?php

namespace Spatie\SlackLogger;

use Spatie\SlackLogger\Exceptions\InvalidClass;
use Spatie\SlackLogger\Exceptions\InvalidUrl;

class Slack
{
    protected string $webhookUrlName = 'default';

    public function in(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function display(string $text): void
    {
        $webhookUrl = config("slack-logger.webhook_urls.{$this->webhookUrlName}");

        $this->ensureUrlIsValid($webhookUrl);
        $this->ensureClassExists($job = config('slack-logger.job'));

        $jobClass = app($job, [
            'text' => $text,
            'webhookUrl' => $webhookUrl,
        ]);

        dispatch($jobClass);
    }

    private function ensureUrlIsValid(string $url): void
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrl();
        }
    }

    private function ensureClassExists(string $class): void
    {
        if (! class_exists($class)) {
            InvalidClass::fromClass($class);
        }
    }
}
