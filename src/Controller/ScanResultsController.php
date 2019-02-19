<?php

namespace App\Controller;

use App\Service\AccessibilityReportHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScanResultsController extends AbstractController
{
    /**
     * @Route("/scan/results", name="scan_results")
     */
    public function index(AccessibilityReportHandler $accessibilityReportHandler)
    {
        $report = $accessibilityReportHandler->get_report_for_single_scan_url(1);

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
