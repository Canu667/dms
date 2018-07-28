<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiAbstractController extends Controller
{
    protected function createJsonResponseFromSerialized(string $serializedData, int $code = 200, array $headers = [])
    {
        return new JsonResponse(
            $serializedData,
            $code,
            $headers,
            true
        );
    }
}
