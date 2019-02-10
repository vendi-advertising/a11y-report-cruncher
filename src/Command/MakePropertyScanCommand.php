<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Property;
use App\Entity\PropertyScan;
use App\Entity\PropertyScanUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakePropertyScanCommand extends AppCommandBase
{
    protected static $defaultName = 'app:make:property-scan';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('property_id', InputArgument::OPTIONAL, 'Property Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $property_id = $input->getArgument('property_id');

        $property = null;
        if($property_id && is_numeric($property_id)){
            $property = $this->get_property_by_id((int) $property_id);
            if(!$property){
                $io->note('Supplied property id not found');
            }
        }

        if(!$property){
            $question = (new Question('What is the Id of the property? '))
                            ->setValidator(
                                function ($property_id) use(&$property){

                                    if(!$property_id){
                                        return;
                                    }

                                    if(!is_numeric($property_id)){
                                        throw new \RuntimeException('Please enter a number for the property Id');
                                    }

                                    $property = $this->get_property_by_id((int) $property_id);

                                    if (!$property) {
                                        throw new \RuntimeException('A property with that Id was not found');
                                    }


                                    return $property_id;
                                }
                            )
            ;
            $property_id = $this->ask_question($question);

            if(!$property_id){
                $io->note('Cancelled');
                return;
            }
        }

        $property_scan_url = new PropertyScanUrl();
        $property_scan_url->setUrl($property->getRootUrl());

        $this->entityManager->persist($property_scan_url);

        $property_scan = new PropertyScan();
        $property_scan->setProperty($property);
        $property_scan->addPropertyScanUrl($property_scan_url);

        $this->entityManager->persist($property_scan);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully created new property scan');
    }
}
