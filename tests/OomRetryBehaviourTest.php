<?php

use Illuminate\Support\Facades\Http;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

/**
 * Simulates the queue worker's per-attempt retry check under catch-block bypass
 * (OOM, SIGKILL, process crash). Models the logic in
 * Illuminate\Queue\Worker::markJobAsFailedIfAlreadyExceedsMaxAttempts(): the
 * attempts counter is checked before fire(), so $tries caps attempts regardless
 * of whether the catch block runs. The $maxExceptions counter, in contrast,
 * only increments inside the catch block.
 *
 * @return array{attempts: int, httpCalls: int, capHit: bool}
 */
function simulateWorkerWithCatchBypass(SendToSlackChannelJob $job, int $safetyCap = 20): array
{
    $attempts = 0;
    $httpCallsBefore = count(Http::recorded());
    $capHit = false;

    while (true) {
        $attempts++;

        // Worker's pre-fire check: if $tries is set to a positive value,
        // fail the job once attempts exceeds that value.
        $maxTries = $job->tries;
        if ($maxTries > 0 && $attempts > $maxTries) {
            break;
        }

        if ($attempts > $safetyCap) {
            $capHit = true;
            break;
        }

        try {
            $job->handle();
            break;
        } catch (\Throwable $exception) {
            // Simulating catch-block bypass: the worker never got here
            // because OOM killed it. $maxExceptions counter is NOT incremented.
            // The loop continues on the next worker cycle.
            continue;
        }
    }

    return [
        'attempts' => $attempts - 1,
        'httpCalls' => count(Http::recorded()) - $httpCallsBefore,
        'capHit' => $capHit,
    ];
}

beforeEach(function () {
    Http::fake([
        'hooks.slack.com/*' => Http::response(['error' => 'invalid_token'], 401),
    ]);
});

it('OLD defaults ($tries = 0) would run indefinitely when the catch block is bypassed', function () {
    // Scenario: a Slack admin rotates the webhook secret. The next dispatch
    // gets a 401 from Slack. Under the OLD design, $maxExceptions = 3 was
    // supposed to stop the retries after three catchable exceptions. But if
    // the worker is killed mid-fire (OOM, container restart, SIGKILL) before
    // the catch block runs, $maxExceptions never increments. $tries = 0 means
    // unlimited, so the job re-fires indefinitely, hammering Slack's 401.
    $oldDefaultsJob = new class(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    ) extends SendToSlackChannelJob {
        public int $tries = 0;           // OLD: unlimited
        public int $maxExceptions = 3;   // OLD: only counts if catch runs
    };

    $result = simulateWorkerWithCatchBypass($oldDefaultsJob, safetyCap: 20);

    // Safety cap of 20 fires. In production, with no safety cap, the loop
    // continues until the broker's retention window closes (hours to days)
    // or an operator intervenes.
    expect($result['capHit'])->toBeTrue();
    expect($result['attempts'])->toBe(20);
    expect($result['httpCalls'])->toBe(20);
});

it('NEW defaults ($tries = 3) cap retries at 3 even when the catch block is bypassed', function () {
    // Same scenario: rotated webhook, 401 response. NEW defaults use $tries
    // as the gate, which is checked on pop (outside the catch block). No
    // matter whether the worker's catch runs, the job fails after 3 attempts.
    $newDefaultsJob = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    );

    $result = simulateWorkerWithCatchBypass($newDefaultsJob, safetyCap: 20);

    expect($result['capHit'])->toBeFalse();
    expect($result['attempts'])->toBe(3);
    expect($result['httpCalls'])->toBe(3);
});

it('proves the fix reduces HTTP calls to a rotated webhook by a factor of at least 6x per incident', function () {
    $oldJob = new class(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    ) extends SendToSlackChannelJob {
        public int $tries = 0;
        public int $maxExceptions = 3;
    };

    $newJob = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    );

    $oldResult = simulateWorkerWithCatchBypass($oldJob, safetyCap: 20);
    $newResult = simulateWorkerWithCatchBypass($newJob, safetyCap: 20);

    // OLD: 20 calls (hit safety cap). NEW: 3 calls. Real-world ratio is worse:
    // the safety cap of 20 is arbitrary; with no cap the OLD path runs until
    // broker retention expires, which for SQS is up to 14 days at default rates.
    expect($oldResult['httpCalls'] / $newResult['httpCalls'])->toBeGreaterThanOrEqual(6);
});
