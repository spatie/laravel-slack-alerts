<?php

namespace Spatie\SlackLogger\Exceptions;

use RuntimeException;

class InvalidClass extends RunTimeException
{
    public static function fromClass(string $name): self
    {
        throw new self(sprintf('Class %s does not exist.', $name));
    }
}
