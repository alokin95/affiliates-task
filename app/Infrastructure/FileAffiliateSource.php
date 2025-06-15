<?php

namespace App\Infrastructure;

use App\Core\Exception\FileParseException;
use App\Core\Storage\FileReaderInterface;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;

readonly class FileAffiliateSource implements AffiliateSourceInterface
{
    public function __construct(
        private FileReaderInterface $reader,
        private string $filePath = 'affiliates.txt',
    ) {}

    /**
     * @return AffiliateDTO[]
     */
    public function getAll(): array
    {
        $raw = $this->reader->read($this->filePath);
        $lines = array_filter(explode("\n", trim($raw)));

        $affiliates = [];
        foreach ($lines as $lineNumber => $line) {
            $data = json_decode($line, true);
            if (!is_array($data)) {
                continue;
            }

            foreach (['affiliate_id', 'name', 'latitude', 'longitude'] as $key) {
                if (!array_key_exists($key, $data)) {
                    throw new FileParseException(
                        "Missing required key '{$key}' on line " . ($lineNumber + 1)
                    );
                }
            }

            $affiliates[] = AffiliateDTO::fromArray($data);
        }

        return $affiliates;
    }
}
