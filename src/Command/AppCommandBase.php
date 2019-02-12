<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Client;
use App\Entity\Property;
use App\Entity\Scanner;
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

    protected function get_all_property_names() : array
    {
        return $this->get_all_names_of_things($this->get_all_properties());
    }

    protected function get_all_scanner_types() : array
    {
        return Scanner::get_entry_types();
    }

    protected function get_all_scanner_type_names() : array
    {
        return $this->get_all_scanner_types();
    }

    protected function get_all_scanner_names() : array
    {
        return $this->get_all_names_of_things($this->get_all_scanners());
    }

    protected function get_scanner_type_by_name(string $name) : ?string
    {
        foreach ($this->get_all_scanner_types() as $s) {
            if ($s === $name) {
                return $name;
            }
        }

        return null;
    }

    protected function get_all_properties() : array
    {
        return $this->get_all_SOMETHING(Property::class);
    }

    protected function get_all_clients() : array
    {
        return $this->get_all_SOMETHING(Client::class);
    }

    protected function get_all_scanners() : array
    {
        return $this->get_all_SOMETHING(Scanner::class);
    }

    protected function get_property_by_name(string $property_name) : ?Property
    {
        //This is flawed because it is not unique
        return $this->get_SOMETHING_by_name($property_name, Property::class);
    }

    protected function get_user_by_email(string $email) : ?User
    {
        return $this->get_SOMETHING_by_SOMETHING_ELSE($email, 'email', User::class);
    }

    protected function get_client_by_name(string $name) : ?Client
    {
        return $this->get_SOMETHING_by_name($name, Client::class);
    }

    protected function get_all_client_names() : array
    {
        $clients =  $this->get_all_clients();

        return $this->get_all_names_of_things($clients);
    }

    protected function get_SOMETHING_by_name(string $name, string $repository)
    {
        return $this->get_SOMETHING_by_SOMETHING_ELSE($name, 'name', $repository);
        ;
    }

    protected function get_SOMETHING_by_SOMETHING_ELSE(string $value, string $property, string $repository)
    {
        return $this
                    ->entityManager
                    ->getRepository($repository)
                    ->findOneBy(
                        [
                            $property => $value,
                        ]
                    )
        ;
    }

    protected function get_all_SOMETHING(string $repository) : array
    {
        return $this
                ->entityManager
                ->getRepository($repository)
                ->findAll()
        ;
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
