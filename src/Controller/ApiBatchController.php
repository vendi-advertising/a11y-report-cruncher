<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ScanUrl;
use App\Repository\ScanUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\PathUtil\Path;

class ApiBatchController extends AbstractController
{
    protected function debug_dump_post(Request $request, KernelInterface $kernel, Filesystem $fileSystem)
    {
        $root_dir = $kernel->getProjectDir();
        $dump_folder = Path::join($root_dir, 'var', 'post_dumps');
        $fileSystem->mkdir($dump_folder);
        $dump_file = Path::join($dump_folder, 'dump_' . date('m-d-Y_hia') . '.' . time() . '.json');

        $body = $request->getContent();
        $fileSystem->dumpFile($dump_file, $body);
    }

    /**
     * @Route("/api/v1/batch/submit", name="api_batch_submit", methods={"GET", "POST"},)
     */
    public function get_report_from_spider(EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage, KernelInterface $kernel, Filesystem $fileSystem, ScanUrlRepository $scanUrlRepository, LoggerInterface $logger)
    {
        $scanner = $tokenStorage->getToken()->getUser();

        if (!$scanner) {
            return JsonResponse::create(
                [
                    'error' => 'Scanner not found',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $logger->info(sprintf('Scanner %1$d dropping off URLs', $scanner->getId()));

        //Turn this on to read from a specific file in the dump folder
        $debug_mode = false;

        if ($debug_mode) {
            $root_dir = $kernel->getProjectDir();
            $dump_folder = Path::join($root_dir, 'var', 'post_dumps');
            $dump_file = Path::join($dump_folder, 'dump_02-14-2019_0902pm.1550178121.json');
            $body = file_get_contents($dump_file);
        } else {
            //If we're in debug mode, we're probably using the browser so we don't want to log it
            $this->debug_dump_post($request, $kernel, $fileSystem);
            $body = $request->getContent();
        }
                
        $parsed = json_decode($body);

        if (!is_array($parsed)) {
            return JsonResponse::create(
                [
                    'error' => 'Non array',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (0 === count($parsed)) {
            return JsonResponse::create(
                [
                    'error' => 'Empty array',
                ],
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        foreach ($parsed as $scanUrlJson) {
            $logger->info(sprintf('Primary URL is %1$s', $scanUrlJson->url));

            $scanUrl = $scanUrlRepository->find($scanUrlJson->scanUrlId);
            if (!$scanUrl) {
                return JsonResponse::create(
                    [
                        'error' => sprintf('Could not find Scan Url with id [%1$s]', $scanUrlJson->scanUrlId),
                    ],
                    Response::HTTP_EXPECTATION_FAILED
                );
            }
            $scanUrl->setContentType($scanUrlJson->contentType);
            $scanUrl->setHttpStatus($scanUrlJson->statusCode);
            $scanUrl->setByteSize($scanUrlJson->byteSize);

            //TODO: This error needs to be logged somewhere
            if ($scanUrlJson->error) {
                $scanUrl->setScanStatus(ScanUrl::SCAN_STATUS_ERROR);
            } else {
                $scanUrl->setScanStatus(ScanUrl::SCAN_STATUS_SUCCESS);
            }
            
            $entityManager->persist($scanUrl);
            
            if (!$scanUrlJson->error) {
                if ($scanUrlJson->subUrlRequestStatus) {
                    $discovered_urls = $scanUrlJson->subUrlRequestStatus->urls;
                    foreach ($discovered_urls as $disc_url) {
                        $logger->info(sprintf('Processing potential URL %1$s', $disc_url));

                        $existing = $scanUrlRepository->findOneBy(
                            [
                                'url' => $disc_url,
                            ]
                        );

                        if ($existing) {
                            $logger->info('URL exists... skipping');
                            continue;
                        }

                        $logger->info(sprintf('Adding URL %1$s', $disc_url));

                        $newScanUrl = new ScanUrl();
                        $newScanUrl->setScan($scanUrl->getScan());
                        $newScanUrl->setUrl($disc_url);

                        $entityManager->persist($newScanUrl);
                    }
                }
            }

            //Flush here because repo findOneBy only queries the database, not what is queued
            $entityManager->flush();
        }

        //Just in case the loop doesn't run, flush once more
        $entityManager->flush();

        //TODO: Return something better
        return new JsonResponse('');
    }

    /**
     * @Route("/api/v1/batch/request", name="api_batch_request", defaults={"_format": "json"}, methods={"GET"},)
     */
    public function request_urls_for_spider(TokenStorageInterface $tokenStorage, ScanUrlRepository $scanUrlRepository, LoggerInterface $logger)
    {
        $scanner = $tokenStorage->getToken()->getUser();

        if (!$scanner) {
            return JsonResponse::create(
                [
                    'error' => 'Scanner not found',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $logger->info(sprintf('Scanner %1$d logged in', $scanner->getId()));

        $urls = $scanUrlRepository->findAllUrlsReadyToScan(15);

        $data = [
            'urls' => [
            ]
        ];

        foreach ($urls as $url) {
            $data['urls'][] = [
                'url' => $url->getUrl(),
                'scanUrlId' => $url->getId(),
            ];
        }

        return new JsonResponse($data);
    }
}
