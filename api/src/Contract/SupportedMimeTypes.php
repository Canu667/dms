<?php

declare(strict_types = 1);

namespace App\Contract;

class SupportedMimeTypes
{
    /**
     * @var array
     */
    private $mimeTypes = [
        'application/pdf',
        'application/x-pdf',
        'text/plain',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'image/png',
        'image/jpeg',
        'image/gif',
        'text/csv',
    ];

    /**
     * @return array
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }
}
