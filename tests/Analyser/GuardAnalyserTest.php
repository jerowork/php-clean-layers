<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\ClassDependenciesParser\Fqn;
use Jerowork\PHPCleanLayers\Analyser\CheckTick;
use Jerowork\PHPCleanLayers\Analyser\GuardAnalyser;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Rule\NotDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use Jerowork\PHPCleanLayers\Test\Analyser\Stub\RuleProcessorStub;
use Jerowork\PHPCleanLayers\Test\Analyser\Stub\RuleWithViolationProcessorStub;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GuardAnalyserTest extends TestCase
{
    private GuardAnalyser $analyser;
    private RuleProcessorStub $ruleProcessor1;
    private RuleWithViolationProcessorStub $ruleProcessor2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->analyser = new GuardAnalyser([
            $this->ruleProcessor1 = new RuleProcessorStub(),
            $this->ruleProcessor2 = new RuleWithViolationProcessorStub(),
        ]);
    }

    /**
     * @test
     */
    public function itShouldAnalyse(): void
    {
        $classDependencies1 = new ClassDependencies('Some/Layer/Class.php');
        $classDependencies1->setFqn(new Fqn('Some\Layer\Class'));
        $classDependencies1->addInlineFqn(new Fqn('Another\Layer\Class'));

        $classDependencies2 = new ClassDependencies('Another/Layer/Class.php');
        $classDependencies2->setFqn(new Fqn('Another\Layer\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Some\Layer\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Third\Layer\Class'));

        $results = [...$this->analyser->analyse(
            [$classDependencies1, $classDependencies2],
            [
                Guard::layer('Some\Layer')->should($rule1 = new NotDependOn('Another\Layer')),
                Guard::layer('Another\Layer')->should($rule2 = new OnlyDependOn('Some\Layer')),
            ],
        )];

        self::assertEquals(
            [
                // $classDependencies1
                //      guard1 'Some\Layer'
                //          $rule1
                new CheckTick(),
                //              $ruleProcessor1
                //                  no violations
                //              $ruleProcessor2
                new Violation('Violation Some\Layer\Class'),
                //      guard2 'Another\Layer'
                //          $rule2
                new CheckTick(),
                //              not part of classDependencies
                // $classDependencies2
                //      guard1 'Some\Layer'
                //          $rule1
                new CheckTick(),
                //              not part of classDependencies
                //      guard2 'Another\Layer'
                //          $rule2
                new CheckTick(),
                //              $ruleProcessor1
                //                  no violations
                //              $ruleProcessor2
                new Violation('Violation Another\Layer\Class'),
            ],
            $results,
        );

        self::assertEquals([
            [new NotDependOn('Another\Layer'), 'Some\Layer\Class'],
            [new OnlyDependOn('Another\Layer', 'Some\Layer'), 'Another\Layer\Class'],
        ], $this->ruleProcessor1->called);

        self::assertEquals([
            [new NotDependOn('Another\Layer'), 'Some\Layer\Class'],
            [new OnlyDependOn('Another\Layer', 'Some\Layer'), 'Another\Layer\Class'],
        ], $this->ruleProcessor2->called);
    }
}
