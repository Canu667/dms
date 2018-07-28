<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;

class DocumentRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Document $document): void
    {
        $this->entityManager->persist($document);
        $this->entityManager->flush($document);
    }

    public function remove(Document $document): void
    {
        $this->entityManager->remove($document);
        $this->entityManager->flush($document);
    }

    public function find(int $id): ?Document
    {
        return $this->entityManager->getRepository(Document::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Document::class)->findAll();
    }
}
