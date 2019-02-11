<?php

namespace App\Controller;

use App\Entity\PropertyScanUrl;
use App\Entity\PropertyScanUrlLog;
use App\Entity\Scanner;
use App\RepositoryPropertyScanUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBatchController extends AbstractController
{
    /**
     * @Route("/api/v1/batch/request", name="api_batch_request", defaults={"_format": "json"},)
     */
    public function request_urls_for_spider(EntityManagerInterface $entityManager)
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

        $data = [
            'urls' => [
            ]
        ];

        foreach($subset as $url){
            $log = (new PropertyScanUrlLog())
                    ->setDirectionOut()
                    ->setStatusSuccess()
                    ->setScanner($scanner)
                    ->setPropertyScanUrl($url)
                ;

            $entityManager->persist($log);

            //This is temporary, but works
            $data['urls'][] = new class($url) implements \JsonSerializable {

                private $url;

                public function __construct($url) {
                    $this->url = $url;
                }

                public function jsonSerialize()
                {
                    return [
                        'url'               => $this->url->getUrl(),
                        'propertyScanUrlId' => $this->url->getId(),
                    ];
                }
            };
        }

        $entityManager->flush();
        // $data['urls'] = [];

        return new JsonResponse($data);
    }
}
