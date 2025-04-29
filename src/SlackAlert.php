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
        $this->dispatchJob(['text' => $text]);
    }

    public function blocks(array $blocks): void
    {
        $this->dispatchJob(['blocks' => $blocks]);
    }

    protected function dispatchJob(array $extraProperties): void
    {
        $allProperties = array_merge(
            $this->getBaseProperties(),
            $extraProperties,
        );

        if (! Config::isEnabled() || empty($allProperties['webhookUrl'])) {
            return;
        }

        $job = Config::getJob($allProperties);

        dispatch(
            $job->onQueue($this->queue())
        );
    }

    protected function getBaseProperties(): array
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        return [
            'webhookUrl' => $webhookUrl,
            'channel' => $this->channel,
            'username' => $this->username,
            'icon_url' => $this->icon_url,
            'icon_emoji' => $this->icon_emoji,
        ];
    }

    protected function queue(): string
    {
        return $this->queue ?? Config::getQueue();
    }
}
