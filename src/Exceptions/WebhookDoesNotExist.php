<?php

namespace GuyWarner\GoogleChatAlerts\Exceptions;

use RuntimeException;

class WebhookDoesNotExist extends RuntimeException
{
    public static function make(string $name): self
    {
        return new self("There is no webhook URL configured with the name `{$name}` make sure you specify one in the `webhook_urls` key of the google-chat-alerts.php config file.");
    }
}
