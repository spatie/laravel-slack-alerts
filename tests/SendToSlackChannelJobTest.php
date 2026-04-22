<?php

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

it('exposes a failed() method that logs a payload-type hint', function () {
    $job = new SendToSlackChannelJob(
        webhookUrl: 'https://hooks.slack.com/services/T/B/secret',
        text: 'hello',
    );

    expect(method_exists($job, 'failed'))->toBeTrue();
});
