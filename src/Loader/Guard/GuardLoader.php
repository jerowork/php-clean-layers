<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Guard;

use Jerowork\PHPCleanLayers\Guard\Guard;

interface GuardLoader
{
    /**
     * @throws InvalidTestException
     *
     * @return list<Guard>
     */
    public function load(string ...$paths): array;
}
