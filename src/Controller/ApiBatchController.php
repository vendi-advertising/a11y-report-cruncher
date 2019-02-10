<?php

namespace App\Controller;

use App\Entity\PropertyScanUrl;
use App\Entity\PropertyScanUrlLog;
use App\Entity\Scanner;
use App\Entity\ScanBatch;
use App\Entity\ScanBatchUrl;
use App\RepositoryPropertyScanUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBatchController extends AbstractController
{
    /**
     * @Route("/api/v1/batch/request", name="api_batch_request", defaults={"_format": "json"},)
     */
    public function batch_get()
    {

        $scanner = $this
                    ->getDoctrine()
                    ->getRepository(Scanner::class)
                    ->findOneBy(
                        [
                            'scannerType' => Scanner::TYPE_SPIDER,
                        ]
                    )
                ;

        if(!$scanner){
            throw new \Exception('No spider scanners registered');
        }


        $urls = $this
                    ->getDoctrine()
                    ->getRepository(PropertyScanUrl::class)
                    ->findAllNotNotSpidered()
                ;

        $subset = array_slice($urls, 0, 5);
        unset($urls);

        $batch = new ScanBatch();
        $batch->setScanner($scanner);
        foreach($subset as $url){

            $sbu = new ScanBatchUrl();
            $sbu->setPropertyScanUrl($url);
            $batch->addScanBatchUrl($sbu);
        }

        $data = [
            'urls' => []
        ];
        foreach($subset as $url){
            $data['urls'][] = $url->getUrl();
        }
        // $data['urls'] = [];

        return new JsonResponse($data);
    }
}
