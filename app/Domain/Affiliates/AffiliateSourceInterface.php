<?php

namespace App\Domain\Affiliates;

use App\DTOs\AffiliateDTO;

interface AffiliateSourceInterface
{
    /**
     * @return AffiliateDto[]
     */
    public function getAll(): array;
}
