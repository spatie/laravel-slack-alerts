<?php

use Illuminate\Support\Facades\Bus;
use Spatie\SlackLogger\Exceptions\JobClassDoesNotExist;
use Spatie\SlackLogger\Exceptions\WebhookUrlNotValid;
use Spatie\SlackLogger\Facades\Slack;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;

beforeEach(function () {
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

it('will throw an exception for a non existing job class', function () {
    config()->set('slack-logger.webhook_urls.default', 'https://test-domain.com');
    config()->set('slack-logger.job', 'non-existing-job');

    Slack::display('test-data');
})->throws(JobClassDoesNotExist::class);


it('will throw an exception for an invalid webhook url', function () {
    config()->set('slack-logger.webhook_urls.default', '');

    Slack::display('test-data');
})->throws(WebhookUrlNotValid::class);

it('will throw an exception for an invalid job class', function () {
    config()->set('slack-logger.webhook_urls.default', 'https://test-domain.com');
    config()->set('slack-logger.job', '');

    Slack::display('test-data');
})->throws(JobClassDoesNotExist::class);
