<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser;

final class Violation
{
    public function __construct(
        public readonly string $message,
    ) {
    }
}
