<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser\RuleProcessor;

use Jerowork\PHPCleanLayers\Analyser\ClassIsPartOfLayerTrait;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\Rule;

final class OnlyDependOnRuleProcessor implements RuleProcessor
{
    use GetClassDependenciesForClassTrait;
    use ClassIsPartOfLayerTrait;

    private const VIOLATION_MESSAGE = '<fg=yellow>%s</> should not depend on <fg=green>%s</> (OnlyDependOn)';

    public function handle(Rule $rule, string $class, array $classDependencies): array
    {
        if (!$rule instanceof OnlyDependOn) {
            return [];
        }

        $violations = [];

        /** @var class-string $classDependency */
        foreach ($this->getClassDependenciesForClass($class, $classDependencies)->getDependencyList() as $classDependency) {
            if ($this->classIsPartOfLayer($classDependency, ...$rule->getLayers())) {
                continue;
            }

            $violations[] = new Violation(sprintf(self::VIOLATION_MESSAGE, $class, $classDependency));
        }

        return $violations;
    }
}
