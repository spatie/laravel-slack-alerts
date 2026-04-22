<?php

use Illuminate\Support\Facades\Http;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

/**
 * Models Illuminate\Queue\Worker::process(). Two retry guards apply each attempt:
 *
 *   - Pre-fire check: markJobAsFailedIfAlreadyExceedsMaxAttempts() reads the
 *     attempts counter from the job payload (incremented on pop by the broker)
 *     and fails the job if it exceeds $tries. Runs regardless of what happens
 *     inside handle().
 *
 *   - Catch-block check: markJobAsFailedIfWillExceedMaxExceptions() reads an
 *     exception counter from the cache, increments it, and fails the job if it
 *     meets $maxExceptions. Runs ONLY if handle()'s exception is caught by the
 *     worker. Bypassed when the process dies mid-fire (OOM, SIGKILL, fatal PHP
 *     error).
 *
 * @param  bool  $catchRuns  true = worker reaches the catch block (normal op);
 *                           false = process dies mid-fire (OOM, SIGKILL, crash).
 * @return array{attempts: int, httpCalls: int, capHit: bool, elapsedMs: float}
 */
function simulateWorker(SendToSlackChannelJob $job, bool $catchRuns, int $safetyCap = 20): array
{
    $attempts = 0;
    $handleCalls = 0;
    $exceptionCount = 0;
    $httpCallsBefore = count(Http::recorded());
    $capHit = false;
    $startedAt = microtime(true);

    while (true) {
        $attempts++;

        if ($job->tries > 0 && $attempts > $job->tries) {
            break;
        }

        if ($attempts > $safetyCap) {
            $capHit = true;
            break;
        }

        try {
            $handleCalls++;
            $job->handle();
            break;
        } catch (\Throwable $exception) {
            if (! $catchRuns) {
                // Process died before reaching this point. Counter stays put.
                continue;
            }

            $exceptionCount++;

            if ($job->maxExceptions > 0 && $exceptionCount >= $job->maxExceptions) {
                break;
            }

            continue;
        }
    }

    return [
        'attempts' => $handleCalls,
        'httpCalls' => count(Http::recorded()) - $httpCallsBefore,
        'capHit' => $capHit,
        'elapsedMs' => (microtime(true) - $startedAt) * 1000,
    ];
}

/**
 * Scenario shared by every test below: a Slack admin rotates the webhook
 * secret. The next dispatch of SendToSlackChannelJob gets a 401 from Slack
 * and ->throw() raises a RequestException.
 */
function makeOldDefaultsJob(): SendToSlackChannelJob
{
    return new class(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    ) extends SendToSlackChannelJob {
        public int $tries = 0;           // pre-change: unlimited
        public int $maxExceptions = 3;   // pre-change: only trips if catch runs
    };
}

function makeNewDefaultsJob(): SendToSlackChannelJob
{
    return new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/rotated',
        text: 'alert',
    );
}

beforeEach(function () {
    Http::fake([
        'hooks.slack.com/*' => Http::response(['error' => 'invalid_token'], 401),
    ]);
});

it('OLD defaults stop at 3 attempts under normal operation', function () {
    // Worker is healthy, handle() throws, the catch runs, $maxExceptions
    // counter increments each attempt. This is the case PR #33 targeted and
    // it still works. Documented here so the PR does not claim the OLD
    // design is broken for everyday failures.
    $result = simulateWorker(makeOldDefaultsJob(), catchRuns: true);

    expect($result['attempts'])->toBe(3);
    expect($result['httpCalls'])->toBe(3);
    expect($result['capHit'])->toBeFalse();

    fwrite(STDERR, "\n  [OLD defaults, catch runs]     3 HTTP calls, bounded\n");
});

it('OLD defaults are unbounded when the catch block is bypassed', function () {
    // Process dies mid-fire (OOM, SIGKILL, container restart, fatal PHP
    // error). The $maxExceptions counter is incremented inside the catch
    // block, so when the catch does not run the counter stays at zero.
    // $tries = 0 means "unlimited", so there is no other ceiling.
    $result = simulateWorker(makeOldDefaultsJob(), catchRuns: false);

    expect($result['capHit'])->toBeTrue();
    expect($result['attempts'])->toBe(20);
    expect($result['httpCalls'])->toBe(20);

    fwrite(STDERR, sprintf(
        "  [OLD defaults, catch bypassed] %d HTTP calls (hit test safety cap; production has no cap)\n",
        $result['attempts'],
    ));
});

it('NEW defaults stop at 3 attempts under normal operation', function () {
    $result = simulateWorker(makeNewDefaultsJob(), catchRuns: true);

    expect($result['attempts'])->toBe(3);
    expect($result['httpCalls'])->toBe(3);

    fwrite(STDERR, "  [NEW defaults, catch runs]     3 HTTP calls, bounded\n");
});

it('NEW defaults stop at 3 attempts when the catch block is bypassed', function () {
    // $tries is checked by Worker::markJobAsFailedIfAlreadyExceedsMaxAttempts()
    // on pop, before fire(). That check runs whether or not the worker's
    // catch block is reached. So the 3-attempt cap holds for both paths.
    $result = simulateWorker(makeNewDefaultsJob(), catchRuns: false);

    expect($result['capHit'])->toBeFalse();
    expect($result['attempts'])->toBe(3);
    expect($result['httpCalls'])->toBe(3);

    fwrite(STDERR, "  [NEW defaults, catch bypassed] 3 HTTP calls, bounded\n\n");
});
