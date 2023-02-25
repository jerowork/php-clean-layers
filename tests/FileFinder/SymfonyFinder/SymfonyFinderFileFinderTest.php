<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\FileFinder\SymfonyFinder;

use Jerowork\PHPCleanLayers\FileFinder\DirectoryNotFoundException;
use Jerowork\PHPCleanLayers\FileFinder\SymfonyFinder\SymfonyFinderFactory;
use Jerowork\PHPCleanLayers\FileFinder\SymfonyFinder\SymfonyFinderFileFinder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SymfonyFinderFileFinderTest extends TestCase
{
    private SymfonyFinderFileFinder $fileFinder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileFinder = new SymfonyFinderFileFinder(new SymfonyFinderFactory());
    }

    /**
     * @test
     */
    public function itShouldFailWhenDirectoryIsNotFound(): void
    {
        self::expectException(DirectoryNotFoundException::class);

        $this->fileFinder->load('invalid-dir');
    }

    /**
     * @test
     */
    public function itShouldGetPhpFilesWithinDirectory(): void
    {
        $files = $this->fileFinder->load(__DIR__ . '/Resources');

        self::assertSame([
            __DIR__ . '/Resources/File.php',
            __DIR__ . '/Resources/File2.PHP',
            __DIR__ . '/Resources/Folder/FileInFolder.php',
        ], $files);
    }
}
