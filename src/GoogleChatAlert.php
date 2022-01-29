<?php

namespace GuyWarner\GoogleChatAlerts;

class GoogleChatAlert
{
    protected string $webhookUrlName = 'default';

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function message(string $text): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        $jobArguments = [
            'text' => $text,
            'type' => 'mrkdown',
            'webhookUrl' => $webhookUrl,
        ];

        $job = Config::getJob($jobArguments);

        dispatch($job);
    }
}
