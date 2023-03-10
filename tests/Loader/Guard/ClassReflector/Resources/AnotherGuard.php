<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Guard\ClassReflector\Resources;

use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;

final class AnotherGuard
{
    #[Test]
    public function guardTest(): Guard
    {
        return Guard::layer('Some\Layer')
            ->should(new OnlyBeAllowedBy('Another\Layer'));
    }
}
