<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\FileFinder;

interface FileFinder
{
    /**
     * @throws DirectoryNotFoundException
     *
     * @return list<string>
     */
    public function load(string $directory): array;
}
