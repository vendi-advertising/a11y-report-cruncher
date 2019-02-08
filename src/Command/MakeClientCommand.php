<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeClientCommand extends Command
{
    protected static $defaultName = 'app:make:client';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new client')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = (new Question('What is the client\'s name? '))
            ->setNormalizer(
                function ($client_name) {
                    return $client_name ? trim($client_name) : '';
                }
            )
            ->setValidator(
                function ($client_name) {

                    $previous_client = $this
                                        ->entityManager
                                        ->getRepository(Client::class)
                                        ->findOneBy(
                                            array(
                                                'name' => $client_name,
                                            )
                                        )
                    ;

                    if($previous_client){
                        throw new \RuntimeException('A client with that name already exists');
                    }


                    return $client_name;
                }
            )
        ;

        $client_name = $helper->ask($input, $output, $question);



        $client = new Client();
        $client->setName($client_name);

        $this->entityManager->persist($client);
        $this->entityManager->flush();


        $io->success("Successfully create new client {$client_name}");
    }
}
