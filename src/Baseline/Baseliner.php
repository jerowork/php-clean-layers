<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Baseline;

use Jerowork\PHPCleanLayers\Analyser\Violation;

interface Baseliner
{
    public function isInBaseline(string $baselineFilePath, Violation $violation): bool;

    public function generateBaseline(string $baselineFilePath, Violation ...$violations): void;
}
