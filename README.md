# Log to Slack using Jobs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-slack-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-logger)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-slack-logger/run-tests?label=tests)](https://github.com/spatie/laravel-slack-logger/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-slack-logger/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-slack-logger/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-slack-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-logger)

Using this package you can send messages to a Slack channel with Laravel Jobs.

When Slack is down, and exception will be thrown. This will be solved when using Jobs.

Here's an example where we'll send a message to a Slack channel.

```php
use  Spatie\SlackLogger\SlackLogger;

SlackLogger::display("{$user->email} has subscribed to the {$newsletter->name} newsletter!");
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-slack-logger.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-slack-logger)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-slack-logger
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-slack-logger-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-slack-logger-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-slack-logger-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-slack-logger = new Spatie\SlackLogger();
echo $laravel-slack-logger->echoPhrase('Hello, Spatie!');
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
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
