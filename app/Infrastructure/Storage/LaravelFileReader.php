<?php

namespace App\Infrastructure\Storage;

use App\Core\Exception\FileNotFoundException;
use App\Core\Exception\FileReadException;
use App\Core\Storage\FileReaderInterface;
use Illuminate\Support\Facades\Storage;

readonly class LaravelFileReader implements FileReaderInterface
{
    public function __construct(
        private string $disk = 'local'
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function read(string $path): string
    {
        if (! Storage::disk($this->disk)->exists($path)) {
            throw FileNotFoundException::fromPath($path);
        }

        $contents = Storage::disk($this->disk)->get($path);

        if (!$contents) {
            throw new FileReadException("File is empty: {$path}");
        }

        return $contents;
    }
}
