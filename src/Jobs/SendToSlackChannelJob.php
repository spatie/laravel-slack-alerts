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

    public function __construct(public string $text)
    {
    }

    public function handle(): void
    {
        $payload = ['text' => $this->text];

        $url = '';

        try {
            Http::post($url, [
                ['body' => json_encode($payload)],
            ]);
        } catch (Exception $e) {
            report($e);
        }
    }
}
