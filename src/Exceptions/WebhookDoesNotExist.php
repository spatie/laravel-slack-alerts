<?php

namespace Spatie\SlackAlerts\Exceptions;

use RuntimeException;

class WebhookDoesNotExist extends RuntimeException
{
    public static function make(string $name): self
    {
        return new self("There is no webhook URL configured with the name `{$name}` make sure you specify one in the `webhook_urls` key of the slack-alerts.php config file.");
    }
}
