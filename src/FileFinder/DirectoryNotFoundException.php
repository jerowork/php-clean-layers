<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\FileFinder;

use Exception;
use Throwable;

final class DirectoryNotFoundException extends Exception
{
    public static function create(string $directory, Throwable $previous): self
    {
        return new self(sprintf('Could not find directory `%s`', $directory), previous: $previous);
    }
}
