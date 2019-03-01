<?php

namespace App\Controller;

use App\Repository\ScanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScansController extends AbstractController
{
    /**
     * @Route("/client/{client_id}/property/{property_id}/scan/list", name="scans", requirements={"client_id"="\d+", "property_id"="\d+"})
     */
    public function index(int $client_id, int $property_id, ScanRepository $scanRepository)
    {
        $scans = $scanRepository
                        ->findBy(
                            [
                                'property' => $property_id,
                            ]
                        );

        return $this
                ->render(
                    'scans/index.html.twig',
                    [
                        'scans' => $scans,
                    ]
                )
            ;
    }
}
