<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Guard\ClassReflector\Stub;

use Jerowork\PHPCleanLayers\Attribute\Test;

final class GuardDoesNotReturnStub
{
    #[Test]
    public function __invoke(): void
    {
    }
}
