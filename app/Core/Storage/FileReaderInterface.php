<?php

namespace App\Core\Storage;

interface FileReaderInterface
{
    public function read(string $path): string;
}
