<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Config;

use Assert\AssertionFailedException;
use Jerowork\PHPCleanLayers\Loader\Config\ConfigFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConfigFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldFailWhenPayloadDoesNotContainParameters(): void
    {
        self::expectException(AssertionFailedException::class);

        ConfigFactory::createFromPayload([]);
    }

    /**
     * @test
     */
    public function itShouldCreateConfigWithFallbackToDefaults(): void
    {
        $config = ConfigFactory::createFromPayload(['parameters' => null]);

        self::assertSame('./src', $config->sourcePath);
        self::assertSame(['./tests/Guards'], $config->guardsPaths);
        self::assertSame('./phpcl-baseline.yaml', $config->baseline);
    }

    /**
     * @test
     */
    public function itShouldCreateConfigWithCustomValues(): void
    {
        $config = ConfigFactory::createFromPayload(['parameters' => [
            'path' => [
                'source' => '/custom/path/to/src',
                'guards' => '/custom/path/to/Guards',
            ],
            'baseline' => './custom-phpcl-baseline.yaml',
        ]]);

        self::assertSame('/custom/path/to/src', $config->sourcePath);
        self::assertSame(['/custom/path/to/Guards'], $config->guardsPaths);
        self::assertSame('./custom-phpcl-baseline.yaml', $config->baseline);
    }

    /**
     * @test
     */
    public function itShouldCreateConfigWithMixedListOfGuardDirectoriesAndClasses(): void
    {
        $config = ConfigFactory::createFromPayload(['parameters' => [
            'path' => [
                'source' => '/custom/path/to/src',
                'guards' => [
                    '/custom/path/to/Guards',
                    '/custom/path/Guard.php',
                ],
            ],
        ]]);

        self::assertSame('/custom/path/to/src', $config->sourcePath);
        self::assertSame(['/custom/path/to/Guards', '/custom/path/Guard.php'], $config->guardsPaths);
    }
}
