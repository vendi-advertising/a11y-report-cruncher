<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Scanner;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeScannerCommand extends AppCommandBase
{
    protected static $defaultName = 'app:make:scanner';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('scanner_name', InputArgument::OPTIONAL, 'Scanner name')
            ->addArgument('scanner_type', InputArgument::OPTIONAL, 'Scanner type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $question = (new Question('What is the name of the scanner?'))
                            ->setValidator(
                                function ($value) {
                                    foreach ($this->get_all_scanners() as $t) {
                                        if (mb_strtolower($t->getName()) === mb_strtolower($value)) {
                                            throw new \RuntimeException('A scanner with that name already exists');
                                        }
                                    }

                                    return $value;
                                }
                            )
        ;
        $scanner_name = $this->get_arg_or_ask('scanner_name', $question);


        $question = new ChoiceQuestion('What type of scanner is this?', $this->get_all_scanner_type_names());
        $scanner_type_name = $this->get_arg_or_ask('scanner_type', $question);

        $scanner_type = $this->get_scanner_type_by_name($scanner_type_name);
        if (!$scanner_type) {
            $io = new SymfonyStyle($this->input, $this->output);
            $io->caution('Could not find the supplied scanner type');
            return;
        }

        $scanner = new Scanner();
        $scanner->setName($scanner_name);
        $scanner->setScannerType($scanner_type);

        $this->entityManager->persist($scanner);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success("Scanner {$scanner_name} created");
    }
}
