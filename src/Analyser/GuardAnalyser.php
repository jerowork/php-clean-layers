<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Analyser;

use Generator;
use Jerowork\ClassDependenciesParser\ClassDependencies;
use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\RuleProcessor;
use Jerowork\PHPCleanLayers\Guard\Guard;

final class GuardAnalyser
{
    use ClassIsPartOfLayerTrait;

    /**
     * @param iterable<RuleProcessor> $ruleProcessors
     */
    public function __construct(
        private readonly iterable $ruleProcessors,
    ) {
    }

    /**
     * @param list<ClassDependencies> $classDependencies
     * @param list<Guard> $guards
     *
     * @return Generator<CheckTick|Violation>
     */
    public function analyse(array $classDependencies, array $guards): Generator
    {
        foreach ($classDependencies as $dependencies) {
            /** @var class-string $class */
            $class = (string) $dependencies->getFqn();

            foreach ($guards as $guard) {
                yield from $this->analyseGuard($guard, $class, $classDependencies);
            }
        }
    }

    /**
     * @param class-string $class
     * @param list<ClassDependencies> $classDependencies
     *
     * @return Generator<CheckTick|Violation>
     */
    private function analyseGuard(Guard $guard, string $class, array $classDependencies): Generator
    {
        foreach ($guard->getRules() as $rule) {
            yield new CheckTick();

            if (!$this->classIsPartOfLayer($class, $guard->getLayer())) {
                continue;
            }

            foreach ($this->ruleProcessors as $ruleProcessor) {
                yield from $ruleProcessor->handle($rule, $class, $classDependencies);
            }
        }
    }
}
