<?php

namespace App\Http\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait for paginating indexed arrays of T items.
 */
trait PaginatesArray
{
    /**
     * @template T
     *
     * @param array<T> $items
     * @param int $perPage
     * @param int $page
     * @param string $path
     * @param array<string,mixed> $query
     * @return LengthAwarePaginator<int, T>
     */
    protected function paginateArray(
        array $items,
        int $perPage,
        int $page,
        string $path,
        array $query
    ): LengthAwarePaginator {
        return new LengthAwarePaginator(
            array_slice($items, ($page - 1) * $perPage, $perPage),
            count($items),
            $perPage,
            $page,
            [
                'path' => $path,
                'query' => $query,
            ]
        );
    }
}
