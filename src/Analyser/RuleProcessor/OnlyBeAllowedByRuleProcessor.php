<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser\RuleProcessor;

use Jerowork\PHPCleanLayers\Analyser\ClassIsPartOfLayerTrait;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\Rule;

final class OnlyBeAllowedByRuleProcessor implements RuleProcessor
{
    use ClassIsPartOfLayerTrait;

    private const VIOLATION_MESSAGE = '<fg=yellow>%s</> is not allowed to use <fg=green>%s</> (OnlyBeAllowedBy)';

    public function handle(Rule $rule, string $class, array $classDependencies): array
    {
        if (!$rule instanceof OnlyBeAllowedBy) {
            return [];
        }

        $violations = [];

        foreach ($classDependencies as $dependencies) {
            if ($dependencies->getFqn() === null) {
                continue;
            }

            /** @var class-string $classDependency */
            $classDependency = (string) $dependencies->getFqn();

            // When class dependency is part of layer, discard
            if ($this->classIsPartOfLayer($classDependency, ...$rule->getLayers())) {
                continue;
            }

            // When not found in class dependencies, discard
            if (!in_array($class, $dependencies->getDependencyList(), true)) {
                continue;
            }

            $violations[] = new Violation(sprintf(self::VIOLATION_MESSAGE, $classDependency, $class));
        }

        return $violations;
    }
}
