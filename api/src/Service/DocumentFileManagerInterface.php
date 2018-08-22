<?php
declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

interface DocumentFileManagerInterface {
    public function finishUpload(File $file, string $directory): File;
    public function remove(string $fileName, string $directory): void;
}