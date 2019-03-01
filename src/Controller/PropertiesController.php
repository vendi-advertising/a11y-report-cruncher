<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertiesController extends AbstractController
{
    /**
     * @Route("/client/{client_id}/property/list", name="properties", requirements={"client_id"="\d+"})
     */
    public function index(int $client_id, PropertyRepository $propertyRepository)
    {
        $properties = $propertyRepository
                        ->findBy(
                            [
                                'client' => $client_id,
                            ]
                        );

        return $this
                ->render(
                    'properties/index.html.twig',
                    [
                        'properties' => $properties,
                    ]
                )
            ;
    }
}
