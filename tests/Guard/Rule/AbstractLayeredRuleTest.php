<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Guard\Rule;

use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Layer\RootLevelClasses;
use Jerowork\PHPCleanLayers\Test\Guard\Rule\Stub\RuleStub1;
use Jerowork\PHPCleanLayers\Test\Guard\Rule\Stub\RuleStub2;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AbstractLayeredRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldConstruct(): void
    {
        $rule = new RuleStub1(
            'Some\Layer',
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
            RegexLayer::create('Another\Layer'),
        );

        self::assertEquals(
            [
                RegexLayer::create('Some\Layer'),
                RegexLayer::create('Another\Layer'),
                new RootLevelClasses(),
            ],
            $rule->getLayers(),
        );
    }

    /**
     * @test
     */
    public function itShouldVerifyIfRuleHasLayer(): void
    {
        $rule = new RuleStub1('Some\Layer', RegexLayer::create('Another\Layer'), new RootLevelClasses());

        self::assertTrue($rule->hasLayer(RegexLayer::create('Some\Layer')));
        self::assertFalse($rule->hasLayer(RegexLayer::create('Third\Layer')));
        self::assertTrue($rule->hasLayer(new RootLevelClasses()));
    }

    /**
     * @test
     */
    public function itShouldMergeTwoLayers(): void
    {
        $rule1 = new RuleStub1(
            RegexLayer::create('Some\Layer'),
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
            RegexLayer::create('Third\Layer'),
        );
        $rule2 = new RuleStub1(
            'Some\Layer',
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
        );

        self::assertEquals([
            RegexLayer::create('Some\Layer'),
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
            RegexLayer::create('Third\Layer'),
        ], $rule1->merge($rule2)->getLayers());
    }

    /**
     * @test
     */
    public function itShouldNotMergeTwoDifferentRules(): void
    {
        $rule1 = new RuleStub1(
            RegexLayer::create('Some\Layer'),
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
            RegexLayer::create('Third\Layer'),
        );
        $rule2 = new RuleStub2(
            'Some\Layer',
            RegexLayer::create('Another\Layer'),
            new RootLevelClasses(),
        );

        self::expectException(LogicException::class);

        $rule1->merge($rule2);
    }
}
