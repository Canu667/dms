<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
