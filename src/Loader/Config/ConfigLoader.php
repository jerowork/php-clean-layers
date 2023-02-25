<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

interface ConfigLoader
{
    /**
     * @throws ConfigFileNotFoundException
     * @throws FailedParseConfigException
     * @throws InvalidConfigFileException
     */
    public function load(string $filePath): Config;
}
