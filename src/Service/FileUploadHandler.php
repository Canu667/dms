<?php
declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileUploadHandler
{
    public function finishUpload(File $file, string $uploadDirectory): File
    {
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        return $file->move($uploadDirectory, $fileName);
    }

    private function generateUniqueFileName(): string
    {
        return md5(uniqid('fileUpload', true));
    }
}
