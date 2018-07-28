<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\DocumentTypeRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiDocumentTypeController extends ApiAbstractController
{
    /**
     * @var DocumentRepository
     */
    private $documentTypeRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(DocumentTypeRepository $documentTypeRepository, SerializerInterface $serializer)
    {
        $this->documentTypeRepository = $documentTypeRepository;
        $this->serializer             = $serializer;
    }

    public function index(): JsonResponse
    {
        return $this->createJsonResponseFromSerialized(
            $this->serializer->serialize($this->documentTypeRepository->findAll(), 'json')
        );
    }
}
