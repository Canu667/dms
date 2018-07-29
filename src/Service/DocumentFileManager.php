<?php
declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class DocumentFileManager
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * FileUploadHandler constructor.
     *
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function finishUpload(File $file, string $directory): File
    {
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        return $file->move($directory, $fileName);
    }

    public function remove(string $fileName, string $directory): void
    {
        $this->fileSystem->remove($directory . '/' . basename($fileName));
    }

    private function generateUniqueFileName(): string
    {
        return md5(uniqid('fileUpload', true));
    }
}
