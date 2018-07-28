<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\DocumentRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiDocumentController extends ApiAbstractController
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(DocumentRepository $documentRepository, SerializerInterface $serializer)
    {
        $this->documentRepository = $documentRepository;
        $this->serializer         = $serializer;
    }

    public function index(): JsonResponse
    {
        return $this->createJsonResponseFromSerialized(
            $this->serializer->serialize($this->documentRepository->findAll(), 'json')
        );
    }

    public function delete(string $id): JsonResponse
    {
        $document = $this->documentRepository->find((int) $id);

        if ($document === null) {
            throw new NotFoundHttpException('Document not found');
        }

        $this->documentRepository->remove($document);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
