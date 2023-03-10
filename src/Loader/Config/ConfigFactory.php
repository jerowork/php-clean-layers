<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Config;

use Assert\Assertion;
use Assert\AssertionFailedException;

/**
 * @phpstan-type ConfigPayload array{
 *  parameters?: null|array{
 *      path?: array{
 *          source?: string,
 *          guards?: string|list<string>
 *      }
 *  }
 * }
 */
final class ConfigFactory
{
    private const DEFAULT_SOURCE_PATH = './src';
    private const DEFAULT_GUARDS_PATH = './tests/Guards';

    /**
     * @param ConfigPayload $payload
     *
     * @throws AssertionFailedException
     */
    public static function createFromPayload(array $payload): Config
    {
        Assertion::keyExists($payload, 'parameters', 'Missing option `parameters`');

        return new Config(
            $payload['parameters']['path']['source'] ?? self::DEFAULT_SOURCE_PATH,
            (array) ($payload['parameters']['path']['guards'] ?? [self::DEFAULT_GUARDS_PATH]),
        );
    }
}
