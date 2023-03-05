<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\ClassDependenciesParser\Fqn;
use Jerowork\ClassDependenciesParser\ImportedFqn;
use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\OnlyDependOnRuleProcessor;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\NotDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OnlyDependOnRuleProcessorTest extends TestCase
{
    private OnlyDependOnRuleProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = new OnlyDependOnRuleProcessor();
    }

    /**
     * @test
     */
    public function itShouldNotHandleIfRuleIsNotTypeOfOnlyDependOn(): void
    {
        self::assertSame([], $this->processor->handle(
            new NotDependOn('Some\Layer'),
            'Some\Layer\Class',
            [],
        ));
    }

    /**
     * @test
     */
    public function itShouldReturnNoViolationWhenClassContainsOnlyPartOfLayers(): void
    {
        $classDependencies = new ClassDependencies('Some/Layer/Class.php');
        $classDependencies->setFqn(new Fqn('Some\Layer\Class'));
        $classDependencies->addInlineFqn(new Fqn('Third\Layer\Sub\Class'));
        $classDependencies->addInlineFqn(new Fqn('Fourth\Layer\Class'));
        $classDependencies->addImportedFqn(new ImportedFqn(new Fqn('Third\Layer\Trait'), false, null));

        $result = $this->processor->handle(
            new OnlyDependOn('Third\Layer', 'Fourth\Layer'),
            'Some\Layer\Class',
            [$classDependencies],
        );

        self::assertSame([], $result);
    }

    /**
     * @test
     */
    public function itShouldReturnViolationWhenClassContainsSomethingNotPartOfLayers(): void
    {
        $classDependencies = new ClassDependencies('Some/Layer/Class.php');
        $classDependencies->setFqn(new Fqn('Some\Layer\Class'));
        $classDependencies->addInlineFqn(new Fqn('Some\Layer\Sub\Class'));
        $classDependencies->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies->addImportedFqn(new ImportedFqn(new Fqn('Fourth\Layer\Trait'), true, null));

        $result = $this->processor->handle(
            new OnlyDependOn('Third\Layer', 'Fourth\Layer'),
            'Some\Layer\Class',
            [$classDependencies],
        );

        self::assertEquals([
            new Violation('<fg=yellow>Some\Layer\Class</> should not depend on <fg=green>Another\Layer\Class</> (OnlyDependOn)'),
            new Violation('<fg=yellow>Some\Layer\Class</> should not depend on <fg=green>Some\Layer\Sub\Class</> (OnlyDependOn)'),
        ], $result);
    }
}
