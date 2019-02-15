<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Property;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakePropertyCommand extends AppCommandBase
{
    protected static $defaultName = 'app:make:property';

    protected function configure()
    {
        $this
            ->setDescription('Create a new client')
            ->addArgument('name', InputArgument::OPTIONAL, 'Property name')
            ->addArgument('url', InputArgument::OPTIONAL, 'Root Url (please don\'t use sub folders')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Allow this to come in on the actual command line
        $name = $input->getArgument('name');
        $url = $input->getArgument('url');

        if (!$name) {
            //If we didn't pass one in, prompt here
            $question = new Question('What is the name of the property? ');
            $name = $this->getHelper('question')->ask($input, $output, $question);

            if (!$name) {
                $io = new SymfonyStyle($input, $output);
                $io->note('No name supplied... exiting');
                return;
            }
        }

        if (!$url) {
            //If we didn't pass one in, prompt here
            $question = (new Question('What is the root url of the property? '))
                            //Force a valid email address
                            ->setValidator(
                                function ($url) {
                                    if (!is_string($url) || !filter_var($url, \FILTER_VALIDATE_URL)) {
                                        throw new \RuntimeException('Please enter a valid url');
                                    }

                                    return $url;
                                }
                            )
            ;
            $url = $this->getHelper('question')->ask($input, $output, $question);

            if (!$url) {
                $io = new SymfonyStyle($input, $output);
                $io->note('No root url supplied... exiting');
                return;
            }
        }

        $client_names = $this->get_all_client_names();

        $question = (new Question('Please enter the client name or press return to cancel'))
                        ->setAutocompleterValues($client_names)
                        ->setValidator(
                            function ($client_name) use ($client_names) {
                                if (!$client_name) {
                                    return '';
                                }

                                if (!in_array($client_name, $client_names)) {
                                    throw new \RuntimeException('Client not found ');
                                }

                                return $client_name;
                            }
                        )
        ;

        $client_name = $this->getHelper('question')->ask($this->input, $this->output, $question);

        if (!$client_name) {
            return;
        }

        $client = $this->get_client_by_name($client_name);
        if (!$client) {
            $io = new SymfonyStyle($this->input, $this->output);
            $io->caution('Could not find the supplied client');
            return;
        }

        $property = new Property();
        $property->setName($name);
        $property->setRootUrl($url);
        $property->setClient($client);

        $this->entityManager->persist($property);

        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success("Successfully created new property {$name}");
    }
}
