<?php

namespace Unit\Collections;

use App\Collections\AffiliateCollection;
use App\DTOs\AffiliateDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AffiliateCollectionTest extends TestCase
{
    public function test_can_construct_with_affiliate_dtos(): void
    {
        $dto1 = new AffiliateDTO(1, 'First', 53.0, -6.0);
        $dto2 = new AffiliateDTO(2, 'Second', 53.1, -6.1);

        $collection = new AffiliateCollection([$dto1, $dto2]);

        $this->assertCount(2, $collection);
        $this->assertContains($dto1, iterator_to_array($collection));
        $this->assertContains($dto2, iterator_to_array($collection));
    }

    public function test_rejects_non_affiliate_items(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AffiliateCollection([(object) ['random' => 'object']]);
    }

    public function test_toArray_returns_associative_arrays(): void
    {
        $dto        = new AffiliateDTO(1, 'Affiliate DTO', 52.5, -6.5);
        $collection = new AffiliateCollection([$dto]);

        $expected = [
            [
                'affiliateId' => 1,
                'name'        => 'Affiliate DTO',
                'latitude'    => 52.5,
                'longitude'   => -6.5,
            ],
        ];

        $this->assertEquals($expected[0], $collection->all()[0]->toArray());
    }

    public function test_iterable_behavior(): void
    {
        $dtos = [
            new AffiliateDTO(3, 'Third', 53.3, -6.3),
            new AffiliateDTO(4, 'Fourth', 53.4, -6.4),
        ];
        $collection = new AffiliateCollection($dtos);

        $iterated = [];
        foreach ($collection as $dto) {
            $this->assertInstanceOf(AffiliateDTO::class, $dto);
            $iterated[] = $dto;
        }

        $this->assertCount(2, $iterated);
        $this->assertSame($dtos, $iterated);
    }
}
