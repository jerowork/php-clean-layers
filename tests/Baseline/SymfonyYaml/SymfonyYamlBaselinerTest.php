<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Test\Baseline\SymfonyYaml;

use Jerowork\PHPCleanLayers\Analyser\Violation;
use Jerowork\PHPCleanLayers\Baseline\SymfonyYaml\SymfonyYamlBaseliner;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * @internal
 */
final class SymfonyYamlBaselinerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SymfonyYamlBaseliner $baseliner;
    private MockInterface&Dumper $dumper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseliner = new SymfonyYamlBaseliner(
            new Parser(),
            $this->dumper = Mockery::mock(Dumper::class),
        );
    }

    /**
     * @test
     */
    public function itShouldReturnFalseIfBaselineIsNotGenerated(): void
    {
        self::assertFalse($this->baseliner->isInBaseline(
            'not-generated.yaml',
            new Violation('An error in baseline'),
        ));
    }

    /**
     * @test
     */
    public function itShouldVerifyIfViolationIsInBaseline(): void
    {
        self::assertTrue($this->baseliner->isInBaseline(
            __DIR__ . '/Resources/baseline.yaml',
            new Violation('An error in baseline'),
        ));

        self::assertFalse($this->baseliner->isInBaseline(
            __DIR__ . '/Resources/baseline.yaml',
            new Violation('An error not in baseline'),
        ));
    }

    /**
     * @test
     */
    public function itShouldGenerateBaseline(): void
    {
        $this->dumper->expects('dump')
            ->with(
                [
                    'violations' => [
                        'An error',
                        'Another error',
                    ],
                ],
                2,
            );

        $this->baseliner->generateBaseline(
            __DIR__ . '/Resources/generated_baseline.yaml',
            new Violation('An error'),
            new Violation('Another error'),
        );
    }
}
