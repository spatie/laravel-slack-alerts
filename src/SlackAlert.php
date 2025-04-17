<?php

namespace Spatie\SlackAlerts;

class SlackAlert
{
    protected string $webhookUrlName = 'default';

    protected ?string $channel = null;

    protected ?string $queue = null;

    protected ?string $username = null;

    protected ?string $icon_url = null;

    protected ?string $icon_emoji = null;

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

    public function onQueue(string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function withUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function withIconURL(string $icon_url): self
    {
        $this->icon_url = $icon_url;

        return $this;
    }

    public function withIconEmoji(string $icon_emoji): self
    {
        $this->icon_emoji = $icon_emoji;

        return $this;
    }

    public function message(string $text): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! Config::isEnabled() || ! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'text' => $text,
            'webhookUrl' => $webhookUrl,
            'channel' => $this->channel,
            'username' => $this->username,
            'icon_url' => $this->icon_url,
            'icon_emoji' => $this->icon_emoji,
        ]);

        dispatch(
            $job->onQueue($this->queue ?? Config::getQueue())
        );
    }

    public function blocks(array $blocks): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! Config::isEnabled() || ! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'blocks' => $blocks,
            'webhookUrl' => $webhookUrl,
            'channel' => $this->channel,
            'username' => $this->username,
            'icon_url' => $this->icon_url,
            'icon_emoji' => $this->icon_emoji,
        ]);

        dispatch(
            $job->onQueue($this->queue ?? Config::getQueue())
        );
    }
}
