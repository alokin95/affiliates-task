<?php

namespace App\Core\Exception;

use RuntimeException;

class FileNotFoundException extends RuntimeException
{
    public static function fromPath(string $path): self
    {
        return new self("File not found at: {$path}");
    }
}
