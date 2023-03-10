<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Guard\ClassReflector\Resources\Directory;

use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Rule\NotBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;

final class SomeGuard
{
    #[Test]
    public function guardTest(): Guard
    {
        return Guard::layer('Some\Layer')
            ->should(new OnlyBeAllowedBy('Another\Layer'));
    }

    #[Test]
    public function guardTest2(): Guard
    {
        return Guard::layer('Another\Layer')
            ->should(new NotBeAllowedBy('Some\Layer'));
    }
}
