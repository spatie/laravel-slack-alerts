<?php

namespace Spatie\SlackLogger\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use function report;

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
        public string $text,
        public string $webhookUrl
    )
    {
    }

    public function handle(): void
    {
        $payload = ['text' => $this->text];

        Http::post($this->webhookUrl, [
            ['body' => json_encode($payload)],
        ]);
    }
}
