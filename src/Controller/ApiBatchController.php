<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBatchController extends AbstractController
{
    /**
     * @Route("/api/v1/batch/request", name="api_batch", defaults={"_format": "json"},)
     */
    public function batch_get()
    {
        $data = [];
        $data['a'] = 'test';
        return new JsonResponse($data);
    }
}
