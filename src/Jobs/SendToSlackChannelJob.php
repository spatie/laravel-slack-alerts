<?php

namespace Spatie\SlackAlerts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendToSlackChannelJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 0;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    public function __construct(
        public string $webhookUrl,
        public ?string $text = null,
        public ?array $blocks = null,
        public ?string $channel = null,
        public ?string $username = null,
        public ?string $iconUrl = null,
        public ?string $iconEmoji = null
    ) {
    }

    public function handle(): void
    {
        $payload = $this->text
            ? ['type' => 'mrkdwn', 'text' => $this->text]
            : ['blocks' => $this->blocks];

        if ($this->channel) {
            $payload['channel'] = $this->channel;
        }

        if ($this->username) {
            $payload['username'] = $this->username;
        }

        if ($this->iconUrl) {
            $payload['icon_url'] = $this->iconUrl;
        }

        if ($this->iconEmoji) {
            $payload['icon_emoji'] = $this->iconEmoji;
        }

        Http::post($this->webhookUrl, $payload)->throw();
    }
}
