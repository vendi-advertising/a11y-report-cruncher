<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Scanner;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $properties = $entityManager
                        ->getRepository(Property::class)
                        ->findAll()
        ;

        $users = $entityManager
                        ->getRepository(User::class)
                        ->findAll()
        ;

        $scanners = $entityManager
                        ->getRepository(Scanner::class)
                        ->findAll()
        ;

        return $this
                ->render(
                    'home/index.html.twig',
                    [
                        'properties' => $properties,
                        'users' => $users,
                        'scanners' => $scanners,
                    ]
                );
    }
}
