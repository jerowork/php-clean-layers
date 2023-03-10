<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Cli\Command;

use Jerowork\ClassDependenciesParser\ClassDependenciesParser;
use Jerowork\PHPCleanLayers\Analyser\CheckTick;
use Jerowork\PHPCleanLayers\Analyser\ClassIsPartOfLayerTrait;
use Jerowork\PHPCleanLayers\Analyser\GuardAnalyser;
use Jerowork\PHPCleanLayers\FileFinder\FileFinder;
use Jerowork\PHPCleanLayers\Loader\Config\ConfigLoader;
use Jerowork\PHPCleanLayers\Loader\Guard\GuardLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GuardCommand extends Command
{
    use ClassIsPartOfLayerTrait;

    protected static $defaultName = 'guard';
    protected static $defaultDescription = 'Run PHPCleanLayers guards';

    public function __construct(
        private readonly ConfigLoader $configLoader,
        private readonly FileFinder $fileFinder,
        private readonly ClassDependenciesParser $classDependenciesParser,
        private readonly GuardLoader $guardClassLoader,
        private readonly GuardAnalyser $guardAnalyser,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Path to config file',
            './phpcl.yaml',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<fg=green>%s</>', $this->getDescription()));
        $output->writeln('');

        // Load requirements
        /** @var string $configPath */
        $configPath = $input->getOption('config');
        $config = $this->configLoader->load($configPath);

        $sourceFiles = $this->fileFinder->load($config->sourcePath);
        $guards = $this->guardClassLoader->load(...$config->guardsPaths);
        $classDependencies = array_map(fn ($sourceFile) => $this->classDependenciesParser->parse($sourceFile), $sourceFiles);

        // Prepare
        $classDependenciesCount = count($classDependencies);

        $guardRulesCount = 0;
        foreach ($guards as $guard) {
            $guardRulesCount += count($guard->getRules());
        }

        $checkCount = $classDependenciesCount * $guardRulesCount;

        $output->writeln(sprintf('Classes: %d', $classDependenciesCount));
        $output->writeln(sprintf('Checks: %d', $checkCount));
        $output->writeln('');

        $progress = new ProgressBar($output, $checkCount);
        $progress->start();

        // Analyse
        $violations = [];
        foreach ($this->guardAnalyser->analyse($classDependencies, $guards) as $result) {
            if ($result instanceof CheckTick) {
                $progress->advance();

                continue;
            }

            $violations[] = $result;
        }

        $progress->finish();

        // Output results
        $output->writeln('');
        $output->writeln('');

        if (count($violations) === 0) {
            $output->writeln('✅ <fg=green>No violations!</>');

            return self::SUCCESS;
        }

        $output->writeln(sprintf('❌ %d violations found!', count($violations)));
        $output->writeln('');

        foreach ($violations as $violation) {
            $output->writeln(sprintf('* %s', $violation->message));
        }

        return self::FAILURE;
    }
}
