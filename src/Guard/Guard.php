<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard;

use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;
use Jerowork\PHPCleanLayers\Guard\Rule\Rule;

final class Guard
{
    private RegexLayer $layer;

    /**
     * @var list<Rule>
     */
    private array $rules = [];

    public function __construct(string|RegexLayer $layer)
    {
        $this->layer = $layer instanceof RegexLayer ? $layer : new RegexLayer($layer);
    }

    public static function layer(string|RegexLayer $layer): self
    {
        return new self($layer);
    }

    public function should(Rule $rule): self
    {
        // Add Guard layer as rule as well, if added as should Rule
        foreach ([OnlyDependOn::class, OnlyBeAllowedBy::class] as $ruleType) {
            if (!$this->hasRule($ruleType) && $rule instanceof $ruleType) {
                $this->rules[] = new $ruleType($this->layer);
            }
        }

        // Merge with existing if present
        foreach ($this->rules as $index => $existingRule) {
            if ($rule::class === $existingRule::class) {
                $this->rules[$index] = $existingRule->merge($rule);

                return $this;
            }
        }

        // Add as new rule
        $this->rules[] = $rule;

        return $this;
    }

    public function getLayer(): RegexLayer
    {
        return $this->layer;
    }

    /**
     * @param class-string<Rule> $ruleType
     */
    public function hasRule(string $ruleType): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule instanceof $ruleType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<Rule>
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
