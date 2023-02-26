<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Guard\Layer;

use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RegexLayerTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateLayer(): void
    {
        $layer = RegexLayer::create('Some/Layer')
            ->excluding('Sub2', 'Sub1')
            ->excluding('Sub3', 'Sub3');

        self::assertSame('Some/Layer', $layer->layer);
        self::assertSame(['Sub1', 'Sub2', 'Sub3'], $layer->getExcluding());
    }

    /**
     * @test
     */
    public function itShouldEqual(): void
    {
        $layer = RegexLayer::create('Some/Layer')
            ->excluding('Sub1', 'Sub2')
            ->excluding('Sub3', 'Sub3');

        self::assertTrue($layer->equals(RegexLayer::create('Some/Layer')
            ->excluding('Sub3', 'Sub1', 'Sub2')));
        self::assertFalse($layer->equals(RegexLayer::create('Some/Layer')));
    }

    /**
     * @test
     */
    public function itShouldVerifyIfClassIsPartOfLayer(): void
    {
        $layer = RegexLayer::create('Some\Layer')
            ->excluding('Sub1', 'Sub2')
            ->excluding('Sub3', 'Sub3');

        self::assertFalse($layer->isPartOf('Another\Layer\Class'));
        self::assertTrue($layer->isPartOf('Some\Layer\Class'));
        self::assertTrue($layer->isPartOf('Some\Layer\Sub4\Class'));
        self::assertFalse($layer->isPartOf('Some\Layer\Sub3\Class'));
    }
}
