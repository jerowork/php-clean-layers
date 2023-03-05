<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Rule;

use Jerowork\PHPCleanLayers\Guard\Layer\Layer;

interface LayeredRule extends Rule
{
    /**
     * @return list<Layer>
     */
    public function getLayers(): array;
}
