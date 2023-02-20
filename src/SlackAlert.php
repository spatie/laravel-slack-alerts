<?php

namespace Spatie\SlackAlerts;

class SlackAlert
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

        if (! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'text' => $text,
            'webhookUrl' => $webhookUrl,
        ]);

        dispatch($job);
    }

    public function blocks(array $blocks): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'blocks' => $blocks,
            'webhookUrl' => $webhookUrl,
        ]);

        dispatch($job);
    }
}
