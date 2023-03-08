<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Analyser\Stub;

use Jerowork\PHPCleanLayers\Analyser\RuleProcessor\RuleProcessor;
use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Guard\Rule\Rule;

final class RuleWithViolationProcessorStub implements RuleProcessor
{
    /**
     * @var list<array{Rule, string}>
     */
    public array $called = [];

    public function handle(Rule $rule, string $class, array $classDependencies): array
    {
        $this->called[] = [$rule, $class];

        return [new Violation('Violation ' . $class)];
    }
}
