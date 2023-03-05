<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Guard;

use Exception;

final class InvalidTestException extends Exception
{
    public static function doesNotReturnGuard(string $methodName): self
    {
        return new self(sprintf('Test `%s` does not return Guard', $methodName));
    }
}
