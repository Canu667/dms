<?php

namespace App\Tests\Repository;

use App\Entity\DocumentType;
use App\Repository\DocumentTypeRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class DocumentTypeRepositoryTest extends TestCase
{
    /**
     * @var EntityManagerInterface|ObjectProphecy
     */
    private $entityManagerProphecy;

    /**
     * @var ObjectRepository|ObjectProphecy
     */
    private $objectRepositoryProphecy;

    public function setUp()
    {
        parent::setUp();

        $this->objectRepositoryProphecy = $this->prophesize(ObjectRepository::class);
        $this->entityManagerProphecy    = $this->prophesize(EntityManagerInterface::class);
    }

    public function testFindAll()
    {
        $records = ['1' => '2', 'asd' => '132435'];
        $this->objectRepositoryProphecy->findAll()
            ->willReturn($records)
            ->shouldBeCalledTimes(1);
        $this->entityManagerProphecy->getRepository(DocumentType::class)
            ->willReturn($this->objectRepositoryProphecy->reveal())
            ->shouldBeCalledTimes(1);

        $repository = new DocumentTypeRepository($this->entityManagerProphecy->reveal());

        $this->assertSame($records, $repository->findAll());
    }
}
