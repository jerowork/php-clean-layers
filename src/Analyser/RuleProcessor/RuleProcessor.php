<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser\RuleProcessor;

use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\Rule;

interface RuleProcessor
{
    /**
     * @param class-string $class
     * @param list<ClassDependencies> $classDependencies
     *
     * @return list<Violation>
     */
    public function handle(Rule $rule, string $class, array $classDependencies): array;
}
