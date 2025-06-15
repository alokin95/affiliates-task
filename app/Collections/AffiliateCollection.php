<?php

namespace App\Collections;

use App\DTOs\AffiliateDTO;

/**
 * @extends TypedCollection<AffiliateDTO>
 */
final class AffiliateCollection extends TypedCollection
{
    /**
     * @param AffiliateDTO[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct(AffiliateDTO::class, $items);
    }
}
