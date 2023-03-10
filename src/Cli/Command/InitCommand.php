<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class InitCommand extends Command
{
    protected static $defaultName = 'init';
    protected static $defaultDescription = 'Initialize PHPCleanLayers';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $output->writeln(sprintf('<fg=green>%s</>', $this->getDescription()));
        $output->writeln('');

        $templates = glob(__DIR__ . '/../../../resources/templates/*');

        if (!is_array($templates)) {
            $output->writeln('<error>Failed to load templates</error>');

            return self::FAILURE;
        }

        foreach ($templates as $template) {
            $fileName = basename($template);
            $destination = sprintf('%s/%s', getcwd(), $fileName);

            if (file_exists($destination)) {
                $question = new ConfirmationQuestion(sprintf('%s already exists, overwrite?', $fileName), false);

                if (!$helper->ask($input, $output, $question)) {
                    $output->writeln(sprintf('> Skipping <fg=yellow>%s</>', $fileName));
                    continue;
                }
            }

            $output->writeln(sprintf('> Copying <fg=yellow>%s</> to root', $fileName));

            copy($template, sprintf('%s/%s', getcwd(), $fileName));
        }

        $output->writeln('');
        $output->writeln('✅ <fg=green>Initialization complete!</>');

        return self::SUCCESS;
    }
}
