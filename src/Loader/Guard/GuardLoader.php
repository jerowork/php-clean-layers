<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Guard;

use Jerowork\PHPCleanLayers\Guard\Guard;

interface GuardLoader
{
    /**
     * @throws DirectoryNotFoundException
     * @throws InvalidTestException
     *
     * @return list<Guard>
     */
    public function load(string $directory): array;
}
