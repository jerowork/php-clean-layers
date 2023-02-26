<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Layer;

interface Layer
{
    public function equals(self $layer): bool;

    /**
     * @param class-string $class
     */
    public function isPartOf(string $class): bool;
}
