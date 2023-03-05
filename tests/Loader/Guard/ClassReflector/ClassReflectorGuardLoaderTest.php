<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Loader\Guard\ClassReflector;

use Jerowork\FileClassReflector\FileFinder\RegexIterator\RegexIteratorFileFinder;
use Jerowork\FileClassReflector\NikicParser\NikicParserClassReflectorFactory;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Rule\NotBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;
use Jerowork\PHPCleanLayers\Loader\Guard\ClassReflector\ClassReflectorGuardLoader;
use Jerowork\PHPCleanLayers\Loader\Guard\DirectoryNotFoundException;
use Jerowork\PHPCleanLayers\Loader\Guard\InvalidTestException;
use Jerowork\PHPCleanLayers\Test\Loader\Guard\ClassReflector\Stub\GuardDoesNotReturnStub;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ClassReflectorGuardLoaderTest extends TestCase
{
    private ClassReflectorGuardLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader = new ClassReflectorGuardLoader(
            new NikicParserClassReflectorFactory(
                new RegexIteratorFileFinder(),
                (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
                new NodeTraverser(),
            ),
        );
    }

    /**
     * @test
     */
    public function itShouldFailWhenDirectoryIsNotFound(): void
    {
        self::expectException(DirectoryNotFoundException::class);

        $this->loader->load('invalid');
    }

    /**
     * @test
     */
    public function itShouldLoadGuards(): void
    {
        $guards = $this->loader->load(__DIR__ . '/Resources');

        self::assertCount(2, $guards);

        [$guard1, $guard2] = $guards;

        self::assertEquals(
            Guard::layer('Some\Layer')->should(new OnlyBeAllowedBy('Another\Layer')),
            $guard1,
        );

        self::assertEquals(
            Guard::layer('Another\Layer')->should(new NotBeAllowedBy('Some\Layer')),
            $guard2,
        );
    }

    /**
     * @test
     */
    public function itShouldFailWhenTestGuardDoesNotReturnGuardInstance(): void
    {
        self::expectException(InvalidTestException::class);
        self::expectExceptionMessage('Test `' . GuardDoesNotReturnStub::class . '::__invoke` does not return Guard');

        $this->loader->load(__DIR__ . '/Stub');
    }
}
