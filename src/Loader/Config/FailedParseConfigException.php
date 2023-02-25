<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

use Exception;
use Throwable;

final class FailedParseConfigException extends Exception
{
    public static function create(string $filePath, Throwable $previous): self
    {
        return new self(sprintf('Could not parse config file `%s`', $filePath), previous: $previous);
    }
}
