<?php declare(strict_types=1);

namespace App\Controller;

use App\Report\SingleSiteRollup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScanResultsController extends AbstractController
{
    /**
     * @Route("/scan/results/{scan_id}", name="scan_results", requirements={"scan_id"="\d+"})
     */
    public function index(int $scan_id, SingleSiteRollup $singleSiteRollup)
    {
        $report = $singleSiteRollup->get_results($scan_id);

        // dd($report);

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
