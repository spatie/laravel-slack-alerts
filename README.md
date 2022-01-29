# Quickly send a message to Google Chat


This package can quickly send alerts to Google Chat. You can use this to notify yourself of any noteworthy events happening in your app.

```php
use GuyWarner\GoogleChatAlerts\Facades\GoogleChatAlert;

GoogleChatAlert::message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

Under the hood, a job is used to communicate with Google Chat. This prevents your app from failing in case Google Chat is down.

Want to send alerts to Discord instead? Check out [laravel-discord-alerts](https://github.com/spatie/laravel-discord-alerts).

Want to send alerts to GoogleChat instead? Check out [laravel-discord-alerts](https://github.com/spatie/laravel-google-chat-alerts).


## Installation

You can install the package via composer:

```bash
composer require guywarner/laravel-google-chat-alerts
```

You can set a `GOOGLE_CHAT_ALERT_WEBHOOK` env variable containing a valid Google Chat webhook URL. You can learn how to get a webhook URL [in the Google Chat API docs](https://api.Google Chat.com/messaging/webhooks).


Alternatively, you can publish the config file with:

```bash
php artisan vendor:publish --tag="google-chat-alerts-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * The webhook URLs that we'll use to send a message to Google Chat.
     */
    'webhook_urls' => [
        'default' => env('GOOGLE_CHAT_ALERT_WEBHOOK'),
    ],

    /*
     * This job will send the message to Google Chat. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => GuyWarner\GoogleChatAlerts\Jobs\SendToGoogleChatChannelJob::class,
];

```

## Usage

To send a message to Google Chat, simply call `GoogleChatAlert::message()` and pass it any message you want.

```php
GoogleChatAlert::message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Using multiple webhooks

You can also use an alternative webhook, by specify extra ones in the config file.

```php
// in config/google-chat-alerts.php

'webhook_urls' => [
    'default' => 'https://hooks.Google Chat.com/services/XXXXXX',
    'marketing' => 'https://hooks.Google Chat.com/services/YYYYYY',
],
```

The webhook to be used can be chosen using the `to` function.

```php
use GuyWarner\GoogleChatAlerts\Facades\GoogleChatAlert;

GoogleChatAlert::to('marketing')->message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Formatting

### Markdown
You can format your messages with markup. Learn how [in the Google Chat API docs](https://Google Chat.com/help/articles/202288908-Format-your-messages).

```php
use GuyWarner\GoogleChatAlerts\Facades\GoogleChatAlert;

GoogleChatAlert::message("A message *with some bold statements* and _some italicized text_.");
```

### Emoji's

You can use the same emoji codes as in Google Chat. This means custom emoji's are also supported.
```php
use GuyWarner\GoogleChatAlerts\Facades\GoogleChatAlert;

GoogleChatAlert::message(":smile: :custom-code:");
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
