<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\FileFinder\SymfonyFinder;

use Symfony\Component\Finder\Finder;

final class SymfonyFinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}
