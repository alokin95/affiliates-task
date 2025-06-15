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
    /** @var class-string<T> */
    private string $className;

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
        $this->className = $className;

        foreach ($items as $item) {
            $this->assertType($item);
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

    /** @return T[] */
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

    /**
     * Returns a new TypedCollection filtered by the callback.
     *
     * @param callable(T): bool $callback
     * @return self<T>
     */
    public function filter(callable $callback): self
    {
        $filtered = array_filter($this->items, $callback);
        return new self($this->className, array_values($filtered));
    }

    /**
     * Returns a new TypedCollection sorted by the callback.
     *
     * @param callable(T, T): int $callback A comparison function like usort()
     * @return self<T>
     */
    public function sort(callable $callback): self
    {
        $items = $this->items;
        usort($items, $callback);
        return new self($this->className, $items);
    }

    private function assertType(mixed $item): void
    {
        if (!$item instanceof $this->className) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid item type: %s (expected %s)',
                    get_debug_type($item),
                    $this->className
                )
            );
        }
    }
}
