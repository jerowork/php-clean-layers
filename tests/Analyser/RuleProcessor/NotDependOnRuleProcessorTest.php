<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\ClassDependenciesParser\Fqn;
use Jerowork\ClassDependenciesParser\ImportedFqn;
use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\NotDependOnRuleProcessor;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\NotDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NotDependOnRuleProcessorTest extends TestCase
{
    private NotDependOnRuleProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = new NotDependOnRuleProcessor();
    }

    /**
     * @test
     */
    public function itShouldNotHandleIfRuleIsNotTypeOfNotDependOn(): void
    {
        self::assertSame([], $this->processor->handle(
            new OnlyDependOn('Some\Layer'),
            'Some\Layer\Class',
            [],
        ));
    }

    /**
     * @test
     */
    public function itShouldReturnNoViolationWhenClassContainsNothingNotPartOfLayers(): void
    {
        $classDependencies = new ClassDependencies('Some/Layer/Class.php');
        $classDependencies->setFqn(new Fqn('Some\Layer\Class'));
        $classDependencies->addInlineFqn(new Fqn('Some\Layer\Sub\Class'));
        $classDependencies->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies->addImportedFqn(new ImportedFqn(new Fqn('Some\Layer\Trait'), false, null));

        $result = $this->processor->handle(
            new NotDependOn('Third\Layer', 'Fourth\Layer'),
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
        $classDependencies->addInlineFqn(new Fqn('Third\Layer\Class'));
        $classDependencies->addImportedFqn(new ImportedFqn(new Fqn('Fourth\Layer\Trait'), true, null));

        $result = $this->processor->handle(
            new NotDependOn('Third\Layer', 'Fourth\Layer'),
            'Some\Layer\Class',
            [$classDependencies],
        );

        self::assertEquals([
            new Violation('<fg=yellow>Some\Layer\Class</> should not depend on <fg=green>Fourth\Layer\Trait</> (NotDependOn)'),
            new Violation('<fg=yellow>Some\Layer\Class</> should not depend on <fg=green>Third\Layer\Class</> (NotDependOn)'),
        ], $result);
    }
}
