<?php

namespace App\Controller;

use App\Entity\PropertyScanUrl;
use App\Entity\PropertyScanUrlLog;
use App\Entity\Scanner;
use App\RepositoryPropertyScanUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiBatchController extends AbstractController
{
    /**
     * @Route("/api/v1/batch/submit", name="api_batch_submit", methods={"GET", "POST"},)
     */
    public function get_report_from_spider(EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage)
    {
        $body = $request->getContent();
        $body = '[{"url":"https://vendiadvertising.com/","contentType":"text/html; charset=UTF-8","statusCode":200,"error":null,"propertyScanUrlId":1,"subUrlRequestStatus":{"error":null,"urls":["https://vendiadvertising.com/what","https://vendiadvertising.com/work","https://vendiadvertising.com/who","https://vendiadvertising.com/insight","https://vendiadvertising.com/vendi-share","https://vendiadvertising.com/contact","https://vendiadvertising.com","https://vendiadvertising.com/portfolio-item/wiscontext","https://vendiadvertising.com/what/brand","https://vendiadvertising.com/what/design","https://vendiadvertising.com/what/digital","https://vendiadvertising.com/what/marketing","https://vendiadvertising.com/what/media","https://vendiadvertising.com/what/research","https://vendiadvertising.com/what/strategy","https://vendiadvertising.com/what/video-photography","https://vendiadvertising.com/what/web-mobile","https://vendiadvertising.com/reel","https://vendiadvertising.com/national-awards","https://vendiadvertising.com/careers","https://vendiadvertising.com/vendi-launch","https://vendiadvertising.com/work/consumer-goods","https://vendiadvertising.com/work/education","https://vendiadvertising.com/work/financial-insurance","https://vendiadvertising.com/work/healthcare","https://vendiadvertising.com/work/manufacturing"]}}]';
        $parsed = json_decode($body);
        if(!is_array($parsed)){
            throw new \Exception('Non array');
        }

        if(0 === count($parsed)){
            throw new \Exception('Empty array');
        }

        $scanner = $tokenStorage->getToken()->getUser();


        foreach($parsed as $obj){

            $propertyScanUrl = $this
                                ->getDoctrine()
                                ->getRepository(PropertyScanUrl::class)
                                ->find((int)$obj->propertyScanUrlId)
                            ;


            $log = (new PropertyScanUrlLog())
                    ->setDirectionIn()
                    ->setScanner($scanner)
                    ->setPropertyScanUrl($propertyScanUrl)
                    ->setContentType($obj->contentType)
            ;

            if(!$obj->error){
                $log->setStatusSuccess();
            }else{
                $log->setStatusError();
                $log->setInfo($obj->error);
            }

            $entityManager->persist($log);
/*
        "url": "https://vendiadvertising.com/",
        "contentType": "text/html; charset=UTF-8",
        "statusCode": 200,
        "error": null,
        "propertyScanUrlId": 1,
        "subUrlRequestStatus": {
            "error": null,
            "urls": [
            ]
        }
 */

        }

        $entityManager->flush();

        return new JsonResponse('');

        // file_put_contents('/var/www/a11y-primary-site/stage/wp-site/a11y-report-cruncher/tmp/data.txt', $data);
        // die;
    }

    /**
     * @Route("/api/v1/batch/request", name="api_batch_request", defaults={"_format": "json"}, methods={"GET"},)
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

        return new JsonResponse($data);
    }
}
