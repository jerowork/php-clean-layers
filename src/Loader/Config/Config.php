<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

final class Config
{
    /**
     * @param list<string> $guardsPaths
     */
    public function __construct(
        public readonly string $sourcePath,
        public readonly array $guardsPaths,
        public readonly string $baseline,
    ) {
    }
}
