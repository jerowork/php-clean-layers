<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser;

use Jerowork\PHPCleanLayers\Guard\Layer\Layer;

trait ClassIsPartOfLayerTrait
{
    /**
     * @param class-string $class
     */
    public function classIsPartOfLayer(string $class, Layer ...$layers): bool
    {
        return array_filter($layers, fn ($layer) => $layer->isPartOf($class)) !== [];
    }
}
