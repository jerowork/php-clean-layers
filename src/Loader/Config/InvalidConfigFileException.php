<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

use Exception;
use Throwable;

final class InvalidConfigFileException extends Exception
{
    public static function create(Throwable $previous): self
    {
        return new self(sprintf('Invalid config file: %s', $previous->getMessage()), previous: $previous);
    }
}
