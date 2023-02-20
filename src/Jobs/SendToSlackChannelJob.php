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

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    public function __construct(
        public string $webhookUrl,
        public ?string $text = null,
        public ?array $blocks = null,
    ) {
    }

    public function handle(): void
    {
        $payload = $this->text
            ? ['type' => 'mrkdwn', 'text' => $this->text]
            : ['blocks' => $this->blocks];

        Http::post($this->webhookUrl, $payload);
    }
}
