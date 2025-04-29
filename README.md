# Quickly send a message to Slack

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-slack-alerts.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-alerts)
[![run-tests](https://github.com/spatie/laravel-slack-alerts/actions/workflows/run-tests.yml/badge.svg)](https://github.com/spatie/laravel-slack-alerts/actions/workflows/run-tests.yml)
[![Check & fix styling](https://github.com/spatie/laravel-slack-alerts/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/spatie/laravel-slack-alerts/actions/workflows/php-cs-fixer.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-slack-alerts.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-alerts)

This package can quickly send alerts to Slack. You can use this to notify yourself of any noteworthy events happening in your app.

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

Under the hood, a job is used to communicate with Slack. This prevents your app from failing in case Slack is down.

Want to send alerts to Discord instead? Check out [laravel-discord-alerts](https://github.com/spatie/laravel-discord-alerts).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-slack-alerts.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-slack-alerts)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-slack-alerts
```

You can set a `SLACK_ALERT_WEBHOOK` env variable containing a valid Slack webhook URL. You can learn how to get a webhook URL [in the Slack API docs](https://api.slack.com/messaging/webhooks).


Alternatively, you can publish the config file with:

```bash
php artisan vendor:publish --tag="slack-alerts-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * Whether the slack alerts are enabled.
     */
    'enabled' => env('SLACK_ALERT_ENABLED', true),

    /*
     * The webhook URLs that we'll use to send a message to Slack.
     */
    'webhook_urls' => [
        'default' => env('SLACK_ALERT_WEBHOOK'),
    ],

    /*
     * This job will send the message to Slack. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => Spatie\SlackAlerts\Jobs\SendToSlackChannelJob::class,
    'queue' => env('SLACK_ALERT_QUEUE', 'default'),
];

```

## Usage

To send a message to Slack, simply call `SlackAlert::message()` and pass it any message you want.

```php
SlackAlert::message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Sending blocks

Slack supports sending rich formatting using their [Block Kit](https://api.slack.com/block-kit) API, you can send a set of blocks using the `blocks()` method:

```php
SlackAlert::blocks([
    [
        "type" => "section",
        "text" => [
        "type" => "mrkdwn",
            "text" => "You have a new subscriber to the {$newsletter->name} newsletter!"
        ]
    ]
]);
```

## Using multiple webhooks

You can also use an alternative webhook, by specify extra ones in the config file.

```php
// in config/slack-alerts.php

'webhook_urls' => [
    'default' => 'https://hooks.slack.com/services/XXXXXX',
    'marketing' => 'https://hooks.slack.com/services/YYYYYY',
],
```

The webhook to be used can be chosen using the `to` function.

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::to('marketing')->message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

### Using a custom webhooks

The `to` function also supports custom webhook urls.

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::to('https://custom-url.com')->message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

### Sending message to an alternative channel

You can send a message to a channel other than the default one for the webhook, by passing it to the `toChannel` function.

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::toChannel('subscription_alerts')->message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Queuing
By default, messages are sent by dispatching the job to the `default` queue.

### Configuring the queue
In `.env` file, add

```dotenv
SLACK_ALERT_QUEUE=queue_name
```

### Changing the queue at runtime
You can queue the job to a different queue than the one defined in config by passing it to the `onQueue` function.

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::onQueue('some-queue')->message("Some message.");
```

## Formatting

### Markdown
You can format your messages with Slack's markup. Learn how [in the Slack API docs](https://slack.com/help/articles/202288908-Format-your-messages).

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message("A message *with some bold statements* and _some italicized text_.");
```

Links are formatted differently in Slack than the classic markdown structure.

```php
SlackAlert::message("<https://spatie.be|This is a link to our homepage>");
```

### Emoji's

You can use the same emoji codes as in Slack. This means custom emoji's are also supported.
```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message(":smile: :custom-code:");

```

### Mentioning

You can use mentions to notify users and groups. Learn how [in the Slack API docs](https://api.slack.com/reference/surfaces/formatting#mentioning-users).
```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message("A message that notifies <@username> and everyone else who is <!here>")

```

### Icon Change

You can change the icon that appears next to the display-name at the top of the message.
```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::withIconURL('https://example.com/tiny-icon.jpg')->message("Some message.");
```

### Display Name Change

You can change the Display-Name that appears next to the display-name at the top of the message.
```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::withUsername('More Descriptive Name')->message("Some message.");
```

### Usage in tests

The package provides a `SlackAlertFake` that you can use to test your code without actually sending messages to Slack.

In your tests, you should call the `fake` method on the `SlackAlert` facade to start faking.

```php
\Spatie\SlackAlerts\Facades\SlackAlert::fake();
```

These methods are available on the fake:

- `expectMessageSentContaining(string $expectedSubstring)`: Expects that at least one message contains the given substring
- `expectMessagesSent($callable = null)`: Expects that at least one message was sent. Optionally you can pass a callable that will get called for each message sent. Return true in one of the calls to make the expectation pass
- `expectNoMessagesSent()`: Expects that no messages were sent
- `expectNumberOfMessagesSent(int $expectedCount)`: Expects that exactly the given number of messages were sent
- `sentMessages()`: Returns all messages that were sent so you can pass the message to your own expectations

Alternatively, you can make use of the classic mocking approach.

```php
// in a test

use Spatie\SlackAlerts\Facades\SlackAlert;

it('will send an alert to Slack', function() {

    SlackAlert::shouldReceive('message')->once();
    
    // execute code here that does send a message to Slack
});
```

Of course, you can also assert that a message wasn't sent to Slack.

```php
// in a test

use Spatie\SlackAlerts\Facades\SlackAlert;

it('will not send an alert to Slack', function() {
    SlackAlert::shouldReceive('message')->never();
    
    // execute code here that doesn't send a message to Slack
});
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Niels Vanpachtenbeke](https://github.com/Nielsvanpach)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Alternatives

If you want to do more complex stuff with Block Kit, we suggest using [slack-php/slack-php-block-kit](https://github.com/slack-php/slack-php-block-kit)
