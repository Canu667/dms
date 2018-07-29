<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *      name="documents",
 *      indexes={
 *          @ORM\Index(name="idx_document_name", columns={"name"})
 *      }
 * )
 * @ORM\Entity
 */
class Document
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DocumentType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentType", inversedBy="documents")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=120, nullable=false)
     */
    private $fileName;

    /**
     * @var UploadedFile
     *
     * @Assert\NotBlank(message="Please upload the document!")
     * @Assert\File()
     *
     * @Exclude()
     */
    private $documentUpload;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): ?DocumentType
    {
        return $this->type;
    }

    public function setType(DocumentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getDocumentUpload(): ?UploadedFile
    {
        return $this->documentUpload;
    }

    public function setDocumentUpload(UploadedFile $documentUpload): self
    {
        $this->documentUpload = $documentUpload;

        return $this;
    }
}
