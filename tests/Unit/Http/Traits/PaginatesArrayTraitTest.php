<?php

namespace Unit\Http\Traits;

use App\Http\Traits\PaginatesArray;
use PHPUnit\Framework\TestCase;

class PaginatesArrayTraitTest extends TestCase
{
    use PaginatesArray;

    public function test_it_paginates_correctly(): void
    {
        $items = range(1, 25); // sample data 1–25
        $perPage = 10;
        $page = 2;

        $paginator = $this->paginateArray(
            $items,
            perPage: $perPage,
            page: $page,
            path: '/test',
            query: ['some' => 'query']
        );

        $this->assertCount($perPage, $paginator->items());
        $this->assertEquals(2, $paginator->currentPage());
        $this->assertEquals(25, $paginator->total());
        $this->assertEquals(3, $paginator->lastPage());
        $this->assertEquals([11,12,13,14,15,16,17,18,19,20], $paginator->items());
    }

    public function test_empty_array_returns_empty_paginator(): void
    {
        $items = [];
        $paginator = $this->paginateArray(
            $items,
            perPage: 5,
            page: 1,
            path: '/empty',
            query: []
        );

        $this->assertCount(0, $paginator->items());
        $this->assertEquals(0, $paginator->total());
    }
}
