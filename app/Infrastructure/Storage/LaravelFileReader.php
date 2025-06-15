<?php

namespace App\Infrastructure\Storage;

use App\Core\Exception\FileNotFoundException;
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
        $content = Storage::disk($this->disk)->get($path);

        if (!$content) {
            throw FileNotFoundException::fromPath($path);
        }

        return $content;
    }
}
