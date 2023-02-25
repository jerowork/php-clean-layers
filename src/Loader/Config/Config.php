<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

final class Config
{
    public function __construct(
        public readonly string $sourcePath,
        public readonly string $guardsPath,
    ) {
    }
}
