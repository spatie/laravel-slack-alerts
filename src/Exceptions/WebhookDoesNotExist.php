<?php

namespace Spatie\SlackLogger\Exceptions;

use Exception;

class WebhookDoesNotExist extends Exception
{
    public static function make(string $name): self
    {
        return new self("There is no webhook URL configured with the name `{$name}` make sure you specify one in the `webhook_urls` key of the slack-logger.php config file.");
    }
}
