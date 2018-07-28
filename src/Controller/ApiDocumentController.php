<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Document;
use App\Form\Type\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\DocumentTypeRepository;
use App\Service\FileUploadHandler;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiDocumentController extends ApiAbstractController
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @var DocumentTypeRepository
     */
    private $documentTypeRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FileUploadHandler
     */
    private $fileUploadHandler;

    public function __construct(
        DocumentRepository $documentRepository,
        DocumentTypeRepository $documentTypeRepository,
        SerializerInterface $serializer,
        FileUploadHandler $fileUploadHandler
    ) {
        $this->documentRepository     = $documentRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->serializer             = $serializer;
        $this->fileUploadHandler      = $fileUploadHandler;
    }

    public function index(): JsonResponse
    {
        return $this->createJsonResponseFromSerialized(
            $this->serializer->serialize($this->documentRepository->findAll(), 'json')
        );
    }

    public function create(Request $request): JsonResponse
    {
        $document = new Document();
        $form     = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid data submitted!');
        }

        $uploadedFile = $document->getDocumentUpload();
        $document->setName($uploadedFile->getClientOriginalName());

        $targetFile = $this->fileUploadHandler->finishUpload(
            $uploadedFile,
            $this->getParameter('documents_directory')
        );

        $document->setType($this->documentTypeRepository->findByType($targetFile->getMimeType()));
        $document->setFileName($targetFile->getFilename());

        $this->documentRepository->add($document);

        return $this->createJsonResponseFromSerialized(
            $this->serializer->serialize($document, 'json')
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
