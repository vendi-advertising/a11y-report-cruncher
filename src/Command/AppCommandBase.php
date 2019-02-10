<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Client;
use App\Entity\Property;
use App\Entity\PropertyScan;
use App\Entity\Scanner;
use App\Entity\ScannerType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AppCommandBase extends Command
{
    protected $input;

    protected $output;

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function get_property_by_id(int $property_id) : ?Property
    {
        return $this
                    ->entityManager
                    ->getRepository(Property::class)
                    ->find($property_id)
        ;
    }

    protected function get_property_scan_by_id(int $id) : ?PropertyScan
    {
        return $this
                    ->entityManager
                    ->getRepository(ScannerType::class)
                    ->findOneBy(
                        [
                            'id' => $id,
                        ]
                    )
        ;
    }

    protected function get_all_names_of_things(array $things) : array
    {
        $names = [];
        array_walk(
            $things,
            function ($thing) use (&$names) {
                $names[] = $thing->getName();
            }
        )
        ;

        return $names;
    }

    protected function get_all_scanner_types() : array
    {
        return $this
                ->entityManager
                ->getRepository(ScannerType::class)
                ->findAll()
        ;
    }

    protected function get_all_scanner_type_names() : array
    {
        return $this->get_all_names_of_things($this->get_all_scanner_types());
    }

    protected function get_all_scanner_names() : array
    {
        return $this->get_all_names_of_things($this->get_all_scanners());
    }

    protected function get_scanner_type_by_name(string $name) : ?ScannerType
    {
        return $this
                    ->entityManager
                    ->getRepository(ScannerType::class)
                    ->findOneBy(
                        [
                            'name' => $name,
                        ]
                    )
        ;
    }

    protected function get_all_scanners() : array
    {
        return $this
                ->entityManager
                ->getRepository(Scanner::class)
                ->findAll()
        ;
    }

    protected function get_user_by_email(string $email) : ?User
    {
        return $this
                    ->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(
                        [
                            'email' => $email,
                        ]
                    )
        ;
    }

    protected function get_client_by_name(string $name) : ?Client
    {
        return $this
                    ->entityManager
                    ->getRepository(Client::class)
                    ->findOneBy(
                        [
                            'name' => $name,
                        ]
                    )
        ;
    }

    protected function get_all_client_names() : array
    {
        $clients =  $this
                     ->entityManager
                        ->getRepository(Client::class)
                        ->findAll()
        ;

        return $this->get_all_names_of_things($clients);
    }

    protected function get_arg_or_ask(string $arg_name, $question)
    {
        $value = $this->input->getArgument($arg_name);

        if (!$value) {
            //If we didn't pass one in, prompt here
            $value = $this->ask_question($question);

            if (!$value) {
                $io = new SymfonyStyle($this->input, $this->output);
                $io->note("No {$arg_name} supplied... exiting");
                return;
            }
        }

        return $value;
    }

    protected function ask_question($question)
    {
        if (is_string($question)) {
            $question = new Question($question);
        }
        return $this->getHelper('question')->ask($this->input, $this->output, $question);
    }

    protected function ask_yes_or_no_question(string $question, bool $default_value) : bool
    {
        $y_or_n = $default_value ? '[Y/n] ' : '[y/N] ';
        $question = new ConfirmationQuestion($question . ' ' . $y_or_n, $default_value);
        return $this->ask_question($question);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        //We need these stored for the helpers
        $this->input = $input;
        $this->output = $output;
    }
}
