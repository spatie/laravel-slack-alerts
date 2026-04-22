<?php

namespace Spatie\SlackAlerts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendToSlackChannelJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * Counter-based exception limit is intentionally disabled because the
     * worker increments it inside the catch block; if the process dies before
     * that point (OOM, SIGKILL) the counter stays at zero and never trips.
     * $tries guards total attempts, $backoff paces retries.
     */
    public int $maxExceptions = 0;

    /** @var array<int, int> seconds between retries */
    public array $backoff = [10, 30, 60];

    public int $timeout = 10;

    public function __construct(
        public string $webhookUrl,
        public ?string $text = null,
        public ?array $blocks = null,
        public ?string $channel = null,
        public ?string $username = null,
        public ?string $icon_url = null,
        public ?string $icon_emoji = null,
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

        if ($this->icon_url) {
            $payload['icon_url'] = $this->icon_url;
        }

        if ($this->icon_emoji) {
            $payload['icon_emoji'] = $this->icon_emoji;
        }

        if ($this->username) {
            $payload['username'] = $this->username;
        }

        Http::timeout($this->timeout)->post($this->webhookUrl, $payload)->throw();
    }

    public function failed(Throwable $exception): void
    {
        Log::error(static::class . ' permanently failed', [
            'payload_type' => $this->text !== null ? 'text' : 'blocks',
            'exception' => $exception->getMessage(),
        ]);
    }
}
