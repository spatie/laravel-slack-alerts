<?php

namespace Spatie\SlackAlerts;

class SlackAlert
{
    protected string $webhookUrlName = 'default';
    protected ?string $channel = null;
    protected ?string $username = null;
    protected ?string $iconUrl = null;
    protected ?string $iconEmoji = null;

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function toChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function username(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function iconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    public function iconEmoji(string $iconEmoji): self
    {
        $this->iconEmoji = $iconEmoji;

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
            'channel' => $this->channel,
            'username' => $this->username,
            'iconUrl' => $this->iconUrl,
            'iconEmoji' => $this->iconEmoji,
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
            'channel' => $this->channel,
            'username' => $this->username,
            'iconUrl' => $this->iconUrl,
            'iconEmoji' => $this->iconEmoji,
        ]);

        dispatch($job);
    }
}
