# Quickly send a message to Slack

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-slack-alerts.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-alerts)
[![run-tests](https://github.com/spatie/laravel-slack-alerts/actions/workflows/run-tests.yml/badge.svg)](https://github.com/spatie/laravel-slack-alerts/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/spatie/laravel-slack-alerts/actions/workflows/phpstan.yml/badge.svg)](https://github.com/spatie/laravel-slack-alerts/actions/workflows/phpstan.yml)
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
];

```

## Usage

To send a message to Slack, simply call `SlackAlert::message()` and pass it any message you want.

```php
SlackAlert::message("You have a new subscriber to the {$newsletter->name} newsletter!");
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

## Formatting

### Markdown
You can format your messages with markup. Learn how [in the Slack API docs](https://slack.com/help/articles/202288908-Format-your-messages).

```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message("A message *with some bold statements* and _some italicized text_.");
```

### Emoji's

You can use the same emoji codes as in Slack. This means custom emoji's are also supported.
```php
use Spatie\SlackAlerts\Facades\SlackAlert;

SlackAlert::message(":smile: :custom-code:");
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Niels Vanpachtenbeke](https://github.com/Nielsvanpach)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
