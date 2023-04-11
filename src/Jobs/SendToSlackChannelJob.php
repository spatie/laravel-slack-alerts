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
        public array $meta = [],
    ) {
    }

    public function handle(): void
    {
        $payload = $this->text
            ? $this->getMessage()
            : $this->getBlocks();

        Http::post($this->webhookUrl, $payload);
    }

    protected function getMessage(): array
    {
        return array_merge(
            $this->meta,
            ['type' => 'mrkdwn', 'text' => $this->text],
        );
    }

    protected function getBlocks(): array
    {
        return ['blocks' => $this->blocks];
    }
}
