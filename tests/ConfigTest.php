<?php

use Spatie\SlackAlerts\Config;

beforeEach(function () {
    config()->set('slack-alerts.webhook_urls.default', 'https://default-domain.com');
});

it('can get a webhook url', function (string $name, string $result) {
    $url = Config::getWebhookUrl($name);

    $this->assertSame($url, $result);
})->with([
    ['default', 'https://default-domain.com'],
    ['https://custom-domain.com', 'https://custom-domain.com'],
]);

it('cannot get a webhook url for an unknown config name', function () {
    expect(Config::getWebhookUrl('non-existing'))->toBeNull();
});
