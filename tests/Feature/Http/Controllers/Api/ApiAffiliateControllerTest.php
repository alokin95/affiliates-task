<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Application\AffiliateDistanceServiceInterface;
use App\Collections\AffiliateCollection;
use App\DTOs\AffiliateDTO;
use App\Http\Controllers\Api\ApiAffiliateController;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;
use Tests\TestCase;

class ApiAffiliateControllerTest extends TestCase
{
    protected MockInterface $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = $this->mock(AffiliateDistanceServiceInterface::class);

        Route::get('/api/affiliates', [ApiAffiliateController::class, 'index']);
    }

    public function test_returns_paginated_affiliates(): void
    {
        $dtos = [
            new AffiliateDTO(1, 'One', 53, -6),
            new AffiliateDTO(2, 'Two', 53, -6),
            new AffiliateDTO(3, 'Three', 53, -6),
        ];

        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection($dtos));

        $response = $this->getJson('/api/affiliates?per_page=2&page=1');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'payload')
            ->assertJsonStructure([
                'status',
                'payload' => [
                    '*' => ['affiliateId', 'name', 'latitude', 'longitude'],
                ],
                'pagination' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_returns_empty_payload_when_none(): void
    {
        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection([]));

        $response = $this->getJson('/api/affiliates');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'payload')
            ->assertJsonPath('pagination.total', 0);
    }

    public function test_non_integer_page_defaults_to_one_or_fails(): void
    {
        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection([
                new AffiliateDTO(1, 'One', 53, -6),
            ]));

        $response = $this->getJson('/api/affiliates?page=abc&per_page=1');

        $response->assertStatus(200)
            ->assertJsonPath('pagination.current_page', 1);
    }

    public function test_non_integer_per_page_defaults_to_ten_or_fails(): void
    {
        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection([
                new AffiliateDTO(1, 'One', 53, -6),
            ]));

        $response = $this->getJson('/api/affiliates?page=1&per_page=10');

        $response->assertStatus(200)
            ->assertJsonPath('pagination.per_page', 10);
    }
}
