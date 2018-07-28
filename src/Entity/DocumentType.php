<?php

declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Table(
 *      name="document_types",
 *      indexes={
 *          @ORM\Index(name="idx_document_type_name", columns={"mime_type"})
 *      }
 * )
 * @ORM\Entity
 */
class DocumentType
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
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=40, nullable=false)
     */
    private $mimeType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="type")
     *
     * @Exclude()
     */
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getProducts(): Collection
    {
        return $this->documents;
    }
}
