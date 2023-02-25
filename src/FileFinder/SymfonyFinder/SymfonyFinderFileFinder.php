<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\FileFinder\SymfonyFinder;

use Jerowork\PHPCleanLayers\FileFinder\DirectoryNotFoundException;
use Jerowork\PHPCleanLayers\FileFinder\FileFinder;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException as SymfonyDirectoryNotFoundException;

final class SymfonyFinderFileFinder implements FileFinder
{
    public function __construct(
        private readonly SymfonyFinderFactory $finderFactory,
    ) {
    }

    public function load(string $directory): array
    {
        $finder = $this->finderFactory->create();

        try {
            $finder->files()
                ->in($directory)
                ->name(['*.php', '*.PHP']);
        } catch (SymfonyDirectoryNotFoundException $exception) {
            throw DirectoryNotFoundException::create($directory, $exception);
        }

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getRealPath();
        }

        return $files;
    }
}
