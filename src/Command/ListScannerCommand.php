<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListScannerCommand extends AppCommandBase
{
    protected static $defaultName = 'app:list:scanner';

    protected function configure()
    {
        $this
            ->setDescription('List all scanners')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanners = $this->get_all_scanners();

        if (0 === count($scanners)) {
            $io = new SymfonyStyle($input, $output);
            $io->error('No scanners found');
            return;
        }

        $data = [];
        foreach ($scanners as $scanner) {
            $data[] = [
                $scanner->getId(),
                $scanner->getName(),
                $scanner->getScannerType(),
                $scanner->getToken(),
            ];
        }

        (new Table($this->output))
            ->setHeaders(['Id', 'Name', 'Type', 'Token',])
            ->setRows($data)
            ->render()
        ;
    }
}
