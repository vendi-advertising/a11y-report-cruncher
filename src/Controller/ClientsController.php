<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientsController extends AbstractController
{
    /**
     * @Route("/client/list", name="clients")
     */
    public function index(ClientRepository $clientRepository)
    {
        $clients = $clientRepository->findAll();

        return $this
                ->render(
                    'clients/index.html.twig',
                    [
                        'clients' => $clients,
                    ]
                )
            ;
    }
}
