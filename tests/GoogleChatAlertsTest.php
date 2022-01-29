<?php

use Illuminate\Support\Facades\Bus;
use GuyWarner\GoogleChatAlerts\Exceptions\JobClassDoesNotExist;
use GuyWarner\GoogleChatAlerts\Exceptions\WebhookUrlNotValid;
use GuyWarner\GoogleChatAlerts\Facades\GoogleChatAlert;
use GuyWarner\GoogleChatAlerts\Jobs\SendToGoogleChatChannelJob;

beforeEach(function () {
    Bus::fake();
});

it('can dispatch a job to send a message to google-chat using the default webhook url', function () {
    config()->set('google-chat-alerts.webhook_urls.default', 'https://test-domain.com');

    GoogleChatAlert::message('test-data');

    Bus::assertDispatched(SendToGoogleChatChannelJob::class);
});

it('can dispatch a job to send a message to google-chat using an alternative webhook url', function () {
    config()->set('google-chat-alerts.webhook_urls.marketing', 'https://test-domain.com');

    GoogleChatAlert::to('marketing')->message('test-data');

    Bus::assertDispatched(SendToGoogleChatChannelJob::class);
});

it('will throw an exception for a non existing job class', function () {
    config()->set('google-chat-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('google-chat-alerts.job', 'non-existing-job');

    GoogleChatAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);


it('will throw an exception for an invalid webhook url', function () {
    config()->set('google-chat-alerts.webhook_urls.default', '');

    GoogleChatAlert::message('test-data');
})->throws(WebhookUrlNotValid::class);

it('will throw an exception for an invalid job class', function () {
    config()->set('google-chat-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('google-chat-alerts.job', '');

    GoogleChatAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);

it('will throw an exception for a missing job class', function () {
    config()->set('google-chat-alerts.webhook_urls.default', 'https://test-domain.com');
    config()->set('google-chat-alerts.job', null);

    GoogleChatAlert::message('test-data');
})->throws(JobClassDoesNotExist::class);
