<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Rule;

use Jerowork\PHPCleanLayers\Guard\Layer\RegexLayer;
use Jerowork\PHPCleanLayers\Guard\Layer\Layer;
use LogicException;

abstract class AbstractLayeredRule implements LayeredRule
{
    /**
     * @var list<Layer>
     */
    private array $layers = [];

    final public function __construct(string|Layer ...$layers)
    {
        foreach ($layers as $layer) {
            $layer = $layer instanceof Layer ? $layer : new RegexLayer($layer);

            if ($this->hasLayer($layer)) {
                continue;
            }

            $this->layers[] = $layer;
        }
    }

    public function merge(Rule $rule): Rule
    {
        if (static::class !== $rule::class) {
            throw new LogicException(sprintf(
                'Cannot merge two different Rules (%s with %s)',
                static::class,
                $rule::class,
            ));
        }

        return new static(...[...$this->layers, ...$rule->getLayers()]);
    }

    public function hasLayer(Layer $layer): bool
    {
        foreach ($this->layers as $existingLayer) {
            if ($existingLayer->equals($layer)) {
                return true;
            }
        }

        return false;
    }

    public function getLayers(): array
    {
        return $this->layers;
    }
}
