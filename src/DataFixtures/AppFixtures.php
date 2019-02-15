<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Property;
use App\Entity\Scan;
use App\Entity\Scanner;
use App\Entity\ScanUrl;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        // parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setName('Vendi Advertising');
        $manager->persist($client);

        $user = new User();
        $user->setEmail('chris@vendiadvertising.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->addClient($client);
        $user->setRoles([USER::ROLE_GLOBAL_ADMIN, USER::ROLE_USER]);
        $manager->persist($user);

        $property = new Property();
        $property->setName('Vendi Website');
        $property->setRootUrl('https://vendiadvertising.com/');
        $property->setClient($client);
        $manager->persist($property);

        $scanner = new Scanner();
        $scanner->setName('crawler-1');
        $scanner->setScannerType(Scanner::TYPE_CRAWLER);
        $manager->persist($scanner);

        $scan = new Scan();
        $scan->setProperty($property);
        $scan->setScanType(Scanner::TYPE_CRAWLER);
        $manager->persist($scan);

        $scanUrl = new ScanUrl();
        $scanUrl->setScan($scan);
        $scanUrl->setUrl($property->getRootUrl());
        $manager->persist($scanUrl);

        $manager->flush();
    }
}
