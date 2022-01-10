<?php

namespace Spatie\SlackLogger\Exceptions;

use Exception;

class WebhookUrlNotValid extends Exception
{
    public static function make(string $name, string $url): self
    {
        return new self("The name `{$name}` webhook contains an invalid url `{$url}`. Make sure you specify a valid URL in the `webhook_urls.{$name}` key of the slack-logger.php config file.");
    }
}
