<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Document;
use App\Form\Type\DocumentType;
use App\Form\Util\FormHelper;
use App\Repository\DocumentRepository;
use App\Repository\DocumentTypeRepository;
use App\Service\DocumentFileManager;
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
     * @var DocumentFileManager
     */
    private $documentFileManager;

    /**
     * @var FormHelper
     */
    private $formHelper;

    public function __construct(
        DocumentRepository $documentRepository,
        DocumentTypeRepository $documentTypeRepository,
        SerializerInterface $serializer,
        DocumentFileManager $documentFileManager,
        FormHelper $formHelper
    ) {
        $this->documentRepository     = $documentRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->serializer             = $serializer;
        $this->documentFileManager    = $documentFileManager;
        $this->formHelper             = $formHelper;
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

        if (!$form->isSubmitted()) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                'Invalid request, data not submitted!'
            );
        }

        if (!$form->isValid()) {
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $this->formHelper->convertErrors($form)
            );
        }

        $uploadedFile = $document->getDocumentUpload();
        $document->setName($uploadedFile->getClientOriginalName());

        $targetFile = $this->documentFileManager->finishUpload(
            $uploadedFile,
            $this->getUploadDirectory()
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

        $this->documentFileManager->remove($document->getFileName(), $this->getUploadDirectory());
        $this->documentRepository->remove($document);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function getUploadDirectory(): string
    {
        return $this->getParameter('documents_directory');
    }
}
