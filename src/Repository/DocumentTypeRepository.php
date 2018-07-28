<?php

namespace App\Repository;

use App\Entity\DocumentType;
use Doctrine\ORM\EntityManagerInterface;

class DocumentTypeRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByType(string $mimeType): ?DocumentType
    {
        return $this->entityManager->getRepository(DocumentType::class)->findOneBy(['mimeType' => $mimeType]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(DocumentType::class)->findAll();
    }
}
