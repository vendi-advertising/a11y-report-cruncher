<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Scan;
use App\Entity\Scanner;
use App\Entity\ScanUrl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeScanCommand extends AppCommandBase
{
    protected static $defaultName = 'app:make:scan';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $property_names = $this->get_all_property_names();

        $question = (new Question('What property should this be associated with? '))
            ->setAutocompleterValues($property_names)
            ->setNormalizer(
                function ($property_name) {
                    return $property_name ? trim($property_name) : '';
                }
            )
            ->setValidator(
                function ($property_name) use ($property_names) {
                    if (!in_array($property_name, $property_names)) {
                        throw new \RuntimeException('Property not found');
                    }


                    return $property_name;
                }
            )
        ;

        $property_name = $this->ask_question($question);
        $property = $this->get_property_by_name($property_name);

        if (!$property) {
            $io = new SymfonyStyle($this->input, $this->output);
            $io->caution('Could not find the supplied property');
            return;
        }

        $question = new ChoiceQuestion(
            'What types of scans should be included?',
            Scanner::get_entry_types(),
            Scanner::TYPE_CRAWLER
        );
        $question->setMultiselect(true);
        $scan_types = $this->ask_question($question);

        $scan = new Scan();
        $scan->setProperty($property);
        $scan->setScanType(Scanner::TYPE_CRAWLER);
        $this->entityManager->persist($scan);

        $scanUrl = new ScanUrl();
        $scanUrl->setUrl($scan->getProperty()->getRootUrl());
        $scanUrl->setScan($scan);
        $this->entityManager->persist($scanUrl);

        $this->entityManager->flush();


        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
