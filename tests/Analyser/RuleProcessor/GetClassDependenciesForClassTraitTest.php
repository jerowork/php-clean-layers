<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\ClassDependenciesParser\Fqn;
use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\GetClassDependenciesForClassTrait;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GetClassDependenciesForClassTraitTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldGetClassDependenciesForClass(): void
    {
        $trait = new class () {
            use GetClassDependenciesForClassTrait;
        };

        $classDependencies1 = new ClassDependencies('Another/Namespace/Class.php');
        $classDependencies1->setFqn(new Fqn('Another\Namespace\Class'));

        $classDependencies2 = new ClassDependencies('Some/Namespace/Class.php');
        $classDependencies2->setFqn(new Fqn('Some\Namespace\Class'));

        self::assertSame($classDependencies2, $trait->getClassDependenciesForClass(
            'Some\Namespace\Class',
            [$classDependencies1, $classDependencies2],
        ));
    }

    /**
     * @test
     */
    public function itShouldFailWhenClassDependenciesNotFoundForClass(): void
    {
        $trait = new class () {
            use GetClassDependenciesForClassTrait;
        };

        $classDependencies1 = new ClassDependencies('Another/Namespace/Class.php');
        $classDependencies1->setFqn(new Fqn('Another\Namespace\Class'));

        $classDependencies2 = new ClassDependencies('Some/Namespace/Class.php');
        $classDependencies2->setFqn(new Fqn('Some\Namespace\Class'));

        self::expectException(LogicException::class);

        $trait->getClassDependenciesForClass(
            'Unknown\Namespace\Class',
            [$classDependencies1, $classDependencies2],
        );
    }
}
