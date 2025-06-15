<?php

namespace Unit\Collections;

use App\Collections\TypedCollection;
use App\DTOs\AffiliateDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TypedCollectionTest extends TestCase
{
    public function test_accepts_correct_type_and_allows_iteration(): void
    {
        $dto1 = new AffiliateDTO(1, 'One', 0, 0);
        $dto2 = new AffiliateDTO(2, 'Two', 1, 1);

        $collection = new TypedCollection(AffiliateDTO::class, [$dto1, $dto2]);

        $this->assertCount(2, $collection);

        $iterated = [];
        foreach ($collection as $item) {
            $this->assertInstanceOf(AffiliateDTO::class, $item);
            $iterated[] = $item;
        }
        $this->assertSame([$dto1, $dto2], $iterated);
    }

    public function test_toArray_converts_dtos_to_arrays(): void
    {
        $dto = new AffiliateDTO(1, 'Affiliate DTO', 1, 1);

        $collection = new TypedCollection(AffiliateDTO::class, [$dto]);

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertEquals([
            [
                'affiliateId' => 1,
                'name'        => 'Affiliate DTO',
                'latitude'    => 1.0,
                'longitude'   => 1.0,
            ],
        ], $array);
    }

    public function test_rejects_invalid_item_type(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TypedCollection(AffiliateDTO::class, [(object) ['foo' => 'bar']]);
    }

    public function test_empty_initial_collection_is_valid(): void
    {
        $collection = new TypedCollection(AffiliateDTO::class, []);
        $this->assertCount(0, $collection);
        $this->assertSame([], $collection->toArray());
    }
}
