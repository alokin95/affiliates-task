<?php

namespace App\Collections;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 */
class TypedCollection implements IteratorAggregate, Countable
{
    /** @var T[] */
    private array $items;

    /**
     * @param class-string<T> $className
     * @param T[] $items
     */
    public function __construct(string $className, array $items = [])
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class {$className} does not exist.");
        }

        foreach ($items as $item) {
            if (!$item instanceof $className) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid item type: %s (expected %s)',
                        get_debug_type($item),
                        $className
                    )
                );
            }
        }

        $this->items = array_values($items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return T[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(
            fn ($item) => method_exists($item, 'toArray') ? $item->toArray() : (array) $item,
            $this->items
        );
    }
}
