<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\FileFinder\SymfonyFinder;

use Jerowork\PHPCleanLayers\FileFinder\SymfonyFinder\SymfonyFinderFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SymfonyFinderFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateNewFinderInstanceEachTime(): void
    {
        $factory = new SymfonyFinderFactory();

        $finder1 = $factory->create();
        $finder2 = $factory->create();

        self::assertNotSame($finder1, $finder2);
    }
}
