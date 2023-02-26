<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Guard\Layer;

use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Layer\RootLevelClasses;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RootLevelClassesTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldEqual(): void
    {
        self::assertTrue((new RootLevelClasses())->equals(new RootLevelClasses()));
        self::assertFalse((new RootLevelClasses())->equals(RegexLayer::create('Some\Layer')));
    }

    /**
     * @test
     */
    public function itShouldPartOfRootLevel(): void
    {
        self::assertFalse((new RootLevelClasses())->isPartOf('Some\Layer'));
        self::assertTrue((new RootLevelClasses())->isPartOf('Some'));
    }
}
