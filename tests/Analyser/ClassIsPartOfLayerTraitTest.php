<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser;

use Jerowork\PHPCleanLayers\Analyser\ClassIsPartOfLayerTrait;
use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ClassIsPartOfLayerTraitTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldVerifyIfClassIsPartOfLayers(): void
    {
        $trait = new class () {
            use ClassIsPartOfLayerTrait;
        };

        self::assertTrue($trait->classIsPartOfLayer(
            'Some\Layer\Class',
            RegexLayer::create('Another\Layer'),
            RegexLayer::create('Some\Layer'),
        ));

        self::assertFalse($trait->classIsPartOfLayer(
            'Some\Layer\Class',
            RegexLayer::create('Another\Layer'),
            RegexLayer::create('Third\Layer'),
        ));
    }
}
