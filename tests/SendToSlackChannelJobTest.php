<?php

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

it('has bounded retry properties on the job class', function () {
    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    expect($job->tries)->toBe(3);
    expect($job->maxExceptions)->toBe(0);
    expect($job->backoff)->toBe([10, 30, 60]);
    expect($job->timeout)->toBe(10);
});

it('exposes a failed() method', function () {
    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    expect(method_exists($job, 'failed'))->toBeTrue();
});

it('throws on 5xx webhook response so the worker marks the attempt failed', function () {
    Http::fake([
        'hooks.slack.com/*' => Http::response(['error' => 'boom'], 500),
    ]);

    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    expect(fn () => $job->handle())->toThrow(RequestException::class);
});

it('throws on 4xx webhook response so retry is bounded by $tries not by silent success', function () {
    Http::fake([
        'hooks.slack.com/*' => Http::response('no_active_hooks', 404),
    ]);

    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/deleted',
        text: 'hello',
    );

    expect(fn () => $job->handle())->toThrow(RequestException::class);
});

it('posts the payload to the configured webhook url', function () {
    Http::fake(['hooks.slack.com/*' => Http::response('ok', 200)]);

    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    $job->handle();

    Http::assertSent(function ($request) use ($job) {
        return $request->url() === $job->webhookUrl
            && str_contains(json_encode($request->data()), 'hello');
    });
});

it('survives serialization and deserialization without losing retry properties', function () {
    $original = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    $restored = unserialize(serialize($original));

    expect($restored->tries)->toBe(3);
    expect($restored->maxExceptions)->toBe(0);
    expect($restored->backoff)->toBe([10, 30, 60]);
    expect($restored->timeout)->toBe(10);
});

it('supports subclass property override', function () {
    $subclass = new class(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    ) extends SendToSlackChannelJob {
        public int $tries = 5;
        public array $backoff = [1, 2, 3, 4, 5];
    };

    expect($subclass->tries)->toBe(5);
    expect($subclass->backoff)->toBe([1, 2, 3, 4, 5]);
    expect($subclass->maxExceptions)->toBe(0);
    expect($subclass->timeout)->toBe(10);
});
