<?php

namespace App\Infrastructure;

use App\Core\Storage\FileReaderInterface;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;

readonly class FileAffiliateSource implements AffiliateSourceInterface
{
    public function __construct(
        private FileReaderInterface $reader,
        private string              $filePath = 'affiliates.txt'
    ) {
    }

    public function getAll(): array
    {
        $lines = explode("\n", trim($this->reader->read($this->filePath)));

        $affiliates = [];

        foreach ($lines as $line) {
            $data = json_decode($line, true);

            if (\is_array($data)) {
                $affiliates[] = AffiliateDto::fromArray($data);
            }
        }

        return $affiliates;
    }
}
