<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Layer;

final class RegexLayer implements Layer
{
    /**
     * @var list<string>
     */
    private array $excluding = [];

    public function __construct(
        public readonly string $layer,
    ) {
    }

    public static function create(string $layer): self
    {
        return new self($layer);
    }

    public function excluding(string ...$excludes): self
    {
        $this->excluding = array_values(array_unique([...$this->excluding, ...$excludes]));

        sort($this->excluding);

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getExcluding(): array
    {
        return $this->excluding;
    }

    public function equals(Layer $layer): bool
    {
        if (!$layer instanceof RegexLayer) {
            return false;
        }

        return $this->getRegex() === $layer->getRegex();
    }

    public function isPartOf(string $class): bool
    {
        return preg_match($this->getRegex(), $class) === 1;
    }

    private function getRegex(): string
    {
        if ($this->excluding === []) {
            return sprintf('#%s#', str_replace('\\', '\\\\', $this->layer));
        }

        return sprintf('#%s#', str_replace('\\', '\\\\', sprintf(
            '%s\(?!%s)',
            $this->layer,
            implode('|', $this->excluding),
        )));
    }
}
