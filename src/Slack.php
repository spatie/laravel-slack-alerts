<?php

namespace Spatie\SlackLogger;

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
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        $jobArguments = [
            'text' => $text,
            'webhookUrl' => $webhookUrl,
        ];

        $job = Config::getJob($jobArguments);

        dispatch($job);
    }
}
