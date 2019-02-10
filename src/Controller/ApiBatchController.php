<?php

namespace App\Controller;

use App\Entity\PropertyScanUrl;
use App\Entity\ScanBatch;
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

        $urls = $this
                    ->getDoctrine()
                    ->getRepository(PropertyScanUrl::class)
                    ->findAllNotNotSpidered()
                ;

        $subset = array_slice($urls, 0, 5);
        unset($urls);

        $batch = new ScanBatch();
        $batch->addScanBatchUrls($subset);

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
