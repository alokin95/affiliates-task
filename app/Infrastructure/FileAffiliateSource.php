<?php

namespace App\Infrastructure;

use App\Collections\AffiliateCollection;
use App\Core\Exception\FileParseException;
use App\Core\Storage\FileReaderInterface;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;

readonly class FileAffiliateSource implements AffiliateSourceInterface
{
    public function __construct(
        private FileReaderInterface $reader,
        private string $filePath = 'affiliates.txt',
    ) {
    }

    public function getAll(): AffiliateCollection
    {
        $raw   = $this->reader->read($this->filePath);
        $lines = array_filter(explode("\n", trim($raw)));

        $affiliates = [];
        foreach ($lines as $lineNumber => $line) {
            $data = json_decode($line, true);
            if (!\is_array($data)) {
                continue;
            }

            foreach (['affiliate_id', 'name', 'latitude', 'longitude'] as $key) {
                if (!\array_key_exists($key, $data)) {
                    throw new FileParseException(
                        "Missing required key '{$key}' on line " . ($lineNumber + 1)
                    );
                }
            }

            /** @var array{affiliate_id:int|string,name:string,latitude:float|string,longitude:float|string} $data */
            $affiliates[] = AffiliateDTO::fromArray($data);
        }

        return new AffiliateCollection($affiliates);
    }
}
