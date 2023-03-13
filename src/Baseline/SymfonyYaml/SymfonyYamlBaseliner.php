<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Baseline\SymfonyYaml;

use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Baseline\Baseliner;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

final class SymfonyYamlBaseliner implements Baseliner
{
    /**
     * @var array{violations?: list<Violation>}
     */
    private array $baseline = [];

    public function __construct(
        private readonly Parser $parser,
        private readonly Dumper $dumper,
    ) {
    }

    public function isInBaseline(string $baselineFilePath, Violation $violation): bool
    {
        if (!isset($this->baseline['violations'])) {
            try {
                /** @var array{violations: list<Violation>} $baseline */
                $baseline = $this->parser->parseFile($baselineFilePath);
                $this->baseline = $baseline;
            } catch (ParseException $exception) {
                if (str_contains($exception->getMessage(), 'does not exist')) {
                    return false;
                }

                throw $exception;
            }
        }

        return in_array($violation->message, $this->baseline['violations'], true);
    }

    public function generateBaseline(string $baselineFilePath, Violation ...$violations): void
    {
        $yaml = $this->dumper->dump([
            'violations' => array_map(fn ($violation) => $violation->message, $violations),
        ], 2);

        file_put_contents($baselineFilePath, $yaml);
    }
}
