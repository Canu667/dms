<?php

declare(strict_types = 1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ApiDocumentControllerTest extends WebTestCase
{
    private const API_ENDPOINT = '/api/documents';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        parent::setUp();

        $this->client     = static::createClient();
        $this->filesystem = new Filesystem();
    }

    public function testEmptyIndex(): void
    {
        $this->client->request('GET', self::API_ENDPOINT);

        $response = $this->client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertSame(json_encode([]), $this->client->getResponse()->getContent());
    }

    public function testCreate(): int
    {
        $filename         = 'valid_filetype.pdf';
        $copiedFilename   = 'valid_filetype ' . time() . '.pdf';
        $originalFilePath = 'tests/files/' . $filename;
        $copiedFilePath   = 'tests/files/' . $copiedFilename;

        $this->filesystem->copy($originalFilePath, $copiedFilePath);

        $file = new UploadedFile(
            $copiedFilePath,
            $copiedFilename
        );
        $this->client->request(
            'POST',
            self::API_ENDPOINT,
            [],
            ['document' => ['documentUpload' => $file]]
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_CREATED);
        $receivedData = json_decode($response->getContent());

        $this->assertObjectHasAttribute(
            'id',
            $receivedData,
            'The response received does not contain an ID field.'
        );

        return $receivedData->id;
    }

    /**
     * @depends testCreate
     */
    public function testDelete(int $id): void
    {
        $this->client->request(
            'DELETE',
            self::API_ENDPOINT . '/' . $id
        );

        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    private function assertJsonResponse(
        Response $response,
        $statusCode = Response::HTTP_OK,
        $contentType = 'application/json'
    ) {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            'Unexpected HTTP status code'
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            'Missing expected Content-Type header'
        );
        $this->assertJson(
            $response->getContent(),
            'Unexpected content format. Invalid json.'
        );
    }
}
