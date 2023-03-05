<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Guard\Rule;

interface Rule
{
    public function merge(Rule $rule): Rule;
}
