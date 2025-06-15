<?php

namespace App\Domain\Affiliates;

use App\Collections\AffiliateCollection;
use App\DTOs\AffiliateDTO;

interface AffiliateSourceInterface
{
    /**
     * @return AffiliateDto[]
     */
    public function getAll(): AffiliateCollection;
}
