<?php
declare(strict_types = 1);

namespace App\Service;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\File;

class AWSDocumentFileManager implements DocumentFileManagerInterface
{
    private $bucket;
    /**
     * @var S3Client
     */
    private $s3Client;

    public function __construct(S3Client $s3Client, string $bucket)
    {
        $this->bucket = $bucket;
        $this->s3Client = $s3Client;
    }

    public function finishUpload(File $file, string $directory): File
    {
        $filename = $this->generateUniqueFileName() . '.' . $file->guessExtension();
        try {
            $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $filename,
                'Body'   => fopen($file->getRealPath(), 'r')
            ]);

            $newFile = new File($filename, false);

            return $newFile;
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo $e->getMessage();
        }
    }

    public function remove(string $fileName, string $directory): void
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $fileName
        ]);
    }

    private function generateUniqueFileName(): string
    {
        return md5(uniqid('fileUpload', true));
    }
}
