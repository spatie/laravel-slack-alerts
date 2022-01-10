<?php

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Spatie\SlackLogger\Exceptions\InvalidUrl;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;
use Spatie\SlackLogger\Slack;

it('can dispatch a job', function () {
    Bus::fake();

    Config::set('slack-logger.webhook_url', 'https://test-domain.com');

    Slack::display('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});

it('cannot dispatch a job with an invalid webhook url', function () {
    Bus::fake();

    Config::set('slack-logger.webhook_url', '');

    $this->expectException(InvalidUrl::class);

    Slack::display('test-data');

    Bus::assertNothingDispatched();
});
