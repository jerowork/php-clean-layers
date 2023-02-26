<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Layer;

/**
 * Layer defining all root level classes, functions, etc.
 * e.g. native PHP functions and classes.
 */
final class RootLevelClasses implements Layer
{
    public function equals(Layer $layer): bool
    {
        return $layer instanceof RootLevelClasses;
    }

    public function isPartOf(string $class): bool
    {
        return preg_match('#^[^\\\\]+$#', $class) === 1;
    }
}
