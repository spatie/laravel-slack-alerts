<?php

use Illuminate\Support\Facades\Bus;
use Spatie\SlackLogger\Exceptions\InvalidUrl;
use Spatie\SlackLogger\Facades\Slack;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;

beforeEach(function() {
    Bus::fake();
});

it('can dispatch a job to send a message to slack using the default webhook url', function () {
    config()->set('slack-logger.webhook_urls.default', 'https://test-domain.com');

    Slack::display('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});

it('can dispatch a job to send a message to slack using an alternative webhook url', function () {
    config()->set('slack-logger.webhook_urls.marketing', 'https://test-domain.com');

    Slack::in('marketing')->display('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});


it('cannot dispatch a job with an invalid webhook url', function () {
    config()->set('slack-logger.webhook_urls.default', '');

    $this->expectException(InvalidUrl::class);

    Slack::display('test-data');

    Bus::assertNothingDispatched();
});
