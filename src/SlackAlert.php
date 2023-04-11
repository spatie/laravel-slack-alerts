<?php

namespace Spatie\SlackAlerts;

use Illuminate\Support\Arr;

class SlackAlert
{
    protected string $webhookUrlName = 'default';
    protected ?string $tag = null;

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function tag(string $tag): self
    {
        $this->tag = $tag;

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
            'meta' => Config::getMeta($this->webhookUrlName, $this->tag),
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

    protected function teest() {

    }
}
