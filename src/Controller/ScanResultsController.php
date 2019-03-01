<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\SingleSiteRollup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScanResultsController extends AbstractController
{
    /**
     * @Route("/client/{client_id}/property/{property_id}/scan/{scan_id}", name="scan_results", requirements={"client_id"="\d+", "property_id"="\d+", "scan_id"="\d+"})
     */
    public function index(int $client_id, int $property_id, int $scan_id, SingleSiteRollup $singleSiteRollup)
    {
        $report = $singleSiteRollup->get_results($scan_id);

        return $this
                ->render(
                    'scan_results/index.html.twig',
                    [
                        'report' => $report,
                    ]
                )
            ;
    }
}
