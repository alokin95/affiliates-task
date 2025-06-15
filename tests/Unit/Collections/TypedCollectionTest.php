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

    public function test_rejects_invalid_item_type(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TypedCollection(AffiliateDTO::class, [(object) ['foo' => 'bar']]);
    }

    public function test_empty_initial_collection_is_valid(): void
    {
        $collection = new TypedCollection(AffiliateDTO::class, []);
        $this->assertCount(0, $collection);
        $this->assertSame([], $collection->all());
    }

    public function test_filter_filters_correctly_based_on_callback(): void
    {
        $dto1 = new AffiliateDTO(1, 'A', 0, 0);
        $dto2 = new AffiliateDTO(2, 'B', 10, 10);
        $dto3 = new AffiliateDTO(3, 'C', 20, 20);

        $collection = new TypedCollection(AffiliateDTO::class, [$dto1, $dto2, $dto3]);

        $filtered = $collection->filter(fn (AffiliateDTO $a) => $a->latitude >= 10.0);

        $this->assertCount(2, $filtered);
        $this->assertSame([$dto2, $dto3], $filtered->all());
    }

    public function test_sort_orders_items_using_callback(): void
    {
        $dto1 = new AffiliateDTO(3, 'C', 0, 0);
        $dto2 = new AffiliateDTO(1, 'A', 1, 1);
        $dto3 = new AffiliateDTO(2, 'B', 2, 2);

        $collection = new TypedCollection(AffiliateDTO::class, [$dto1, $dto2, $dto3]);

        $sorted = $collection->sort(fn (AffiliateDTO $a, AffiliateDTO $b): int => $a->affiliateId <=> $b->affiliateId);

        $this->assertInstanceOf(TypedCollection::class, $sorted);
        $this->assertCount(3, $sorted);
        $this->assertSame([$dto2, $dto3, $dto1], $sorted->all());
    }

}
