<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Property;
use App\Entity\PropertyScan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakePropertyScanCommand extends Command
{
    protected static $defaultName = 'app:make:property-scan';

    private $entityManager;

    private $input;
    private $output;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('property_id', InputArgument::OPTIONAL, 'Property Id')
        ;
    }

    protected function get_property_by_id(int $property_id) : ?Property
    {
        return $this
                    ->entityManager
                    ->getRepository(Property::class)
                    ->find($property_id)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //We need these stored for the helpers
        $this->input = $input;
        $this->output = $output;

        $property_id = $input->getArgument('property_id');
        if (!$property_id) {
            //If we didn't pass one in, prompt here
            $question = new Question('What is the Id of the property? ');
            $property_id = $this->getHelper('question')->ask($input, $output, $question);
        }

        $property = null;

        if (is_numeric($property_id)) {
            $property = $this->get_property_by_id((int) $property_id);
        }

        if (!$property) {
            $io = new SymfonyStyle($input, $output);
            $io->note('Property not found... exiting');
            return;
        }

        $property_scan = new PropertyScan();
        $property_scan->setProperty($property);

        $this->entityManager->persist($property_scan);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully created new property scan');
    }
}
