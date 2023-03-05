<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use LogicException;

trait GetClassDependenciesForClassTrait
{
    /**
     * @param list<ClassDependencies> $classDependencies
     */
    public function getClassDependenciesForClass(string $class, array $classDependencies): ClassDependencies
    {
        foreach ($classDependencies as $dependencies) {
            if ((string) $dependencies->getFqn() !== $class) {
                continue;
            }

            return $dependencies;
        }

        throw new LogicException(sprintf('ClassDependencies not found for %s', $class));
    }
}
