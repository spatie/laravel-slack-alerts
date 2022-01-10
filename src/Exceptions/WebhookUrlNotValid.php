<?php

namespace Spatie\SlackAlerts\Exceptions;

use Exception;

class WebhookUrlNotValid extends Exception
{
    public static function make(string $name, string $url): self
    {
        return new self("The name `{$name}` webhook contains an invalid url `{$url}`. Make sure you specify a valid URL in the `webhook_urls.{$name}` key of the slack-alerts.php config file.");
    }
}
