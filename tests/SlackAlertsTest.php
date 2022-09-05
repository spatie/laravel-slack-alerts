<?php

use Illuminate\Support\Facades\Bus;
use Spatie\SlackAlerts\Exceptions\JobClassDoesNotExist;
use Spatie\SlackAlerts\Exceptions\WebhookUrlNotValid;
use Spatie\SlackAlerts\Facades\SlackAlert;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

beforeEach(function () {
    Bus::fake();
});

it('can dispatch a job to send a message to slack using the default webhook url', function () {
    config()->set('slack-alerts.webhook_urls.default', 'https://test-domain.com');

    SlackAlert::message('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});

it('can dispatch a job to send a message to slack using an alternative webhook url', function () {
    config()->set('slack-alerts.webhook_urls.marketing', 'https://test-domain.com');

    SlackAlert::to('marketing')->message('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});

it('will throw an exception for a non existing job class', function () {
    config()->set('slack-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('slack-alerts.job', 'non-existing-job');

    SlackAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);

it('will not throw an exception for an empty webhook url', function () {
    config()->set('slack-alerts.webhook_urls.default', '');

    SlackAlert::message('test-data');
})->expectNotToPerformAssertions();

it('will throw an exception for an invalid webhook url', function () {
    config()->set('slack-alerts.webhook_urls.default', 'not-an-url');

    SlackAlert::message('test-data');
})->throws(WebhookUrlNotValid::class);

it('will throw an exception for an invalid job class', function () {
    config()->set('slack-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('slack-alerts.job', '');

    SlackAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);

it('will throw an exception for a missing job class', function () {
    config()->set('slack-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('slack-alerts.job', null);

    SlackAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);
