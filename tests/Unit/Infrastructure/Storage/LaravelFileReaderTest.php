<?php

namespace Tests\Unit\Infrastructure\Storage;

use App\Core\Exception\FileNotFoundException;
use App\Infrastructure\Storage\LaravelFileReader;
use App\Core\Exception\FileReadException;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaravelFileReaderTest extends TestCase
{
    public function test_read_returns_file_contents_when_exists_and_not_empty(): void
    {
        Storage::fake('local');
        $path = 'affiliates.txt';
        $contents = "line1\nline2";
        Storage::disk('local')->put($path, $contents);

        $reader = new LaravelFileReader('local');
        $this->assertSame($contents, $reader->read($path));
    }

    public function test_read_throws_exception_when_file_missing(): void
    {
        Storage::fake('local');
        $reader = new LaravelFileReader('local');

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("File not found at: affiliates.txt");

        $reader->read('affiliates.txt');
    }

    public function test_read_throws_exception_when_file_empty(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('empty.txt', '');

        $reader = new LaravelFileReader('local');

        $this->expectException(FileReadException::class);
        $this->expectExceptionMessage("File is empty: empty.txt");

        $reader->read('empty.txt');
    }
}
