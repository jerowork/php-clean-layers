<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\ClassDependenciesParser\Fqn;
use Jerowork\ClassDependenciesParser\ImportedFqn;
use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\NotBeAllowedByRuleProcessor;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\NotBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NotBeAllowedByRuleProcessorTest extends TestCase
{
    private NotBeAllowedByRuleProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = new NotBeAllowedByRuleProcessor();
    }

    /**
     * @test
     */
    public function itShouldNotHandleIfRuleIsNotTypeOfNotBeAllowedBy(): void
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
    public function itShouldReturnNoViolationWhenNoClassesNotAllowedUseClass(): void
    {
        $classDependencies1 = new ClassDependencies('Another/Layer/Class.php');
        $classDependencies1->setFqn(new Fqn('Another\Layer\Class'));
        $classDependencies1->addInlineFqn(new Fqn('Some\Layer\Class'));
        $classDependencies1->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies1->addImportedFqn(new ImportedFqn(new Fqn('Some\Layer\Trait'), false, null));

        $classDependencies2 = new ClassDependencies('Third/Layer/Class.php');
        $classDependencies2->setFqn(new Fqn('Third\Layer\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Some\Layer\Sub\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies2->addImportedFqn(new ImportedFqn(new Fqn('Some\Layer\Trait'), false, null));

        $result = $this->processor->handle(
            new NotBeAllowedBy('Third\Layer', 'Fourth\Layer'),
            'Some\Layer\Class',
            [$classDependencies1, $classDependencies2],
        );

        self::assertSame([], $result);
    }

    /**
     * @test
     */
    public function itShouldReturnViolationsWhenSomeClassesNotAllowedUseClass(): void
    {
        $classDependencies1 = new ClassDependencies('Another/Layer/Class.php');
        $classDependencies1->setFqn(new Fqn('Another\Layer\Class'));
        $classDependencies1->addInlineFqn(new Fqn('Some\Layer\Class'));
        $classDependencies1->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies1->addImportedFqn(new ImportedFqn(new Fqn('Some\Layer\Trait'), false, null));

        $classDependencies2 = new ClassDependencies('Third/Layer/Class.php');
        $classDependencies2->setFqn(new Fqn('Third\Layer\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Some\Layer\Sub\Class'));
        $classDependencies2->addInlineFqn(new Fqn('Another\Layer\Class'));
        $classDependencies2->addImportedFqn(new ImportedFqn(new Fqn('Some\Layer\Trait'), false, null));

        $result = $this->processor->handle(
            new NotBeAllowedBy('Another\Layer', 'Fourth\Layer'),
            'Some\Layer\Class',
            [$classDependencies1, $classDependencies2],
        );

        self::assertEquals([
            new Violation('<fg=yellow>Another\Layer\Class</> is not allowed to use <fg=green>Some\Layer\Class</> (NotBeAllowedBy)'),
        ], $result);
    }
}
