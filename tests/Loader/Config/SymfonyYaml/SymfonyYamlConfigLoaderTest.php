<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Config\SymfonyYaml;

use Jerowork\PHPCleanLayers\Loader\Config\ConfigFileNotFoundException;
use Jerowork\PHPCleanLayers\Loader\Config\FailedParseConfigException;
use Jerowork\PHPCleanLayers\Loader\Config\InvalidConfigFileException;
use Jerowork\PHPCleanLayers\Loader\Config\SymfonyYaml\SymfonyYamlConfigLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser;

/**
 * @internal
 */
final class SymfonyYamlConfigLoaderTest extends TestCase
{
    private SymfonyYamlConfigLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader = new SymfonyYamlConfigLoader(new Parser());
    }

    /**
     * @test
     */
    public function itShouldFailWhenConfigFileIsNotFound(): void
    {
        self::expectException(ConfigFileNotFoundException::class);

        $this->loader->load('fake-file.yaml');
    }

    /**
     * @test
     */
    public function itShouldFailWhenConfigFileCannotBeParsed(): void
    {
        self::expectException(FailedParseConfigException::class);

        $this->loader->load(__DIR__ . '/Resources/cannot_be_parsed.yaml');
    }

    /**
     * @test
     */
    public function itShouldFailWhenConfigFileContentsAreInvalid(): void
    {
        self::expectException(InvalidConfigFileException::class);

        $this->loader->load(__DIR__ . '/Resources/invalid.yaml');
    }
}
