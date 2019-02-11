<?php

declare(strict_types=1);

namespace App\Command;

use App\Demo\DemoCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DemoInitCommand extends AppCommandBase
{
    protected static $defaultName = 'app:demo:init';

    protected function configure()
    {
        $this
            ->setDescription('Run a bunch of commands to get a fresh install ready to use')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        //Clear the screen
        $output->write(sprintf("\033\143"));

        $io->title('Demo setup');
        $io->text(
                [
                    'This command is intended to be run after the initial database is created in order to get a stable',
                    'system up in running for demonstration and/or development purposes. If at any point one of the',
                    'individual sub-commands fails, we can\'t guarantee that state of the system so you are encouraged',
                    'to reset the database and start over.'
                ]
            )
        ;

        if (!$io->confirm('Are you ready to being?')) {
            $io->warning('Demp setup cancelled');
            exit;
        }

        $commands = [
                new DemoCommand('app:make:client', 'Client'),
                new DemoCommand('app:make:user', 'User'),
                new DemoCommand('app:make:property', 'Proprty'),
        ];

        $io->progressStart(count($commands));
        foreach ($commands as $demo_command) {
            $io->newLine();

            $io->section($demo_command->title);

            if (!$io->confirm($demo_command->question)) {
                $io->text('Skipping');
                $io->progressAdvance();
                continue;
            }

            $command = $this->getApplication()->find($demo_command->command_string);

            $arguments = [
                'command' => $demo_command->command_string,
            ];

            $sub_input = new ArrayInput($arguments);
            $return_code = $command->run($sub_input, $output);
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->newLine();

        $io->success('Demo setup complete');
    }
}
