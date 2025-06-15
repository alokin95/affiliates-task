<?php

namespace App\Collections;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @template T as object
 * @implements IteratorAggregate<int, T>
 */
class TypedCollection implements IteratorAggregate, Countable
{
    /** @var class-string<T> */
    private string $className;

    /** @var list<T> */
    private array $items;

    /**
     * @param class-string<T> $className
     * @param T[] $items
     */
    public function __construct(string $className, array $items = [])
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class $className does not exist.");
        }
        $this->className = $className;

        foreach ($items as $item) {
            $this->assertType($item);
        }

        $this->items = array_values($items);
    }

    /**
     * @return ArrayIterator<int, T>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return list<T>
     */
    public function all(): array
    {
        return $this->items;
    }


    /**
     * Filters items by callback.
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
     * Sorts items using a comparison function.
     *
     * @param callable(T, T): int $callback
     * @return self<T>
     */
    public function sort(callable $callback): self
    {
        $items = $this->items;
        usort($items, $callback);
        return new self($this->className, $items);
    }

    /**
     * @param mixed $item
     */
    private function assertType(mixed $item): void
    {
        if (!is_object($item) || !$item instanceof $this->className) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid item type: %s (expected instance of %s)',
                    get_debug_type($item),
                    $this->className
                )
            );
        }
    }
}
