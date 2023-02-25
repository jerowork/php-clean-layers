<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config\SymfonyYaml;

use Assert\AssertionFailedException;
use Jerowork\PHPCleanLayers\Loader\Config\Config;
use Jerowork\PHPCleanLayers\Loader\Config\ConfigFactory;
use Jerowork\PHPCleanLayers\Loader\Config\ConfigFileNotFoundException;
use Jerowork\PHPCleanLayers\Loader\Config\ConfigLoader;
use Jerowork\PHPCleanLayers\Loader\Config\FailedParseConfigException;
use Jerowork\PHPCleanLayers\Loader\Config\InvalidConfigFileException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;
use Throwable;

/**
 * @phpstan-import-type ConfigPayload from ConfigFactory
 */
final class SymfonyYamlConfigLoader implements ConfigLoader
{
    public function __construct(private readonly Parser $parser)
    {
    }

    public function load(string $filePath): Config
    {
        try {
            /** @var ConfigPayload|null $payload */
            $payload = $this->parser->parseFile($filePath);
        } catch (ParseException $exception) {
            if (str_contains($exception->getMessage(), 'does not exist')) {
                throw ConfigFileNotFoundException::create($filePath, $exception);
            }

            throw FailedParseConfigException::create($filePath, $exception);
        } catch (Throwable $exception) {
            throw FailedParseConfigException::create($filePath, $exception);
        }

        try {
            return ConfigFactory::createFromPayload((array) $payload);
        } catch (AssertionFailedException $exception) {
            throw InvalidConfigFileException::create($exception);
        }
    }
}
