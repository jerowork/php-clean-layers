<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Guard;

use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Rule\NotBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\NotDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GuardTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateGuard(): void
    {
        $guard = Guard::layer($layer = new RegexLayer('Some\Layer'));

        self::assertSame($layer, $guard->getLayer());
    }

    /**
     * @test
     */
    public function itShouldCreateGuardWithStringLayer(): void
    {
        $guard = Guard::layer('Some\Layer');

        self::assertEquals(new RegexLayer('Some\Layer'), $guard->getLayer());
    }

    /**
     * @test
     */
    public function itShouldAddRules(): void
    {
        $guard = Guard::layer('Some\Layer')
            ->should($rule1 = new NotBeAllowedBy('Not\Allowed\By'))
            ->should($rule2 = new NotDependOn('Not\Depend\On'));

        self::assertSame([$rule1, $rule2], $guard->getRules());
    }

    /**
     * @test
     */
    public function itShouldMergeAddedRules(): void
    {
        $guard = Guard::layer('Some\Layer')
            ->should(new NotBeAllowedBy('Not\Allowed\By'))
            ->should(new NotBeAllowedBy('Not\Allowed\By2'))
            ->should(new NotDependOn('Not\Depend\On'));

        self::assertEquals([
            new NotBeAllowedBy(
                'Not\Allowed\By',
                'Not\Allowed\By2',
            ),
            new NotDependOn('Not\Depend\On'),
        ], $guard->getRules());
    }

    /**
     * @test
     */
    public function itShouldAddLayerAsRuleWhenOnlyDependOnIsAdded(): void
    {
        $guard = Guard::layer('Some\Layer')
            ->should(new OnlyDependOn('Only\Depend\On'));

        self::assertEquals([
            new OnlyDependOn(
                'Some\Layer',
                'Only\Depend\On',
            ),
        ], $guard->getRules());
    }

    /**
     * @test
     */
    public function itShouldAddLayerAsRuleWhenOnlyAllowedByIsAdded(): void
    {
        $guard = Guard::layer('Some\Layer')
            ->should(new OnlyBeAllowedBy('Only\Depend\On'));

        self::assertEquals([
            new OnlyBeAllowedBy(
                'Some\Layer',
                'Only\Depend\On',
            ),
        ], $guard->getRules());
    }

    /**
     * @test
     */
    public function itShouldVerifyIfGuardHasRuleByType(): void
    {
        $guard = Guard::layer('Some\Layer')
            ->should(new OnlyBeAllowedBy('Only\Depend\On'));

        self::assertTrue($guard->hasRule(OnlyBeAllowedBy::class));
        self::assertFalse($guard->hasRule(NotBeAllowedBy::class));
    }
}
