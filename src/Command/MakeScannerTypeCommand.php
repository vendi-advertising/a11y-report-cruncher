<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\ScannerType;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeScannerTypeCommand extends AppCommandBase
{
    protected static $defaultName = 'app:make:scanner-type';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('scanner_type', InputArgument::OPTIONAL, 'Scanner type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $question = (new Question('What type of scanner is this?'))
                            ->setValidator(
                                function ($value) {
                                    foreach ($this->get_all_scanner_types() as $t) {
                                        if (mb_strtolower($t->getName()) === mb_strtolower($value)) {
                                            throw new \RuntimeException('A scanner type with that name already exists');
                                        }
                                    }

                                    return $value;
                                }
                            )
        ;

        $scanner_type_name = $this->get_arg_or_ask('scanner_type', $question);

        $scanner_type = new ScannerType();
        $scanner_type->setName($scanner_type_name);

        $this->entityManager->persist($scanner_type);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success("Scanner type {$scanner_type_name} created");
    }
}
