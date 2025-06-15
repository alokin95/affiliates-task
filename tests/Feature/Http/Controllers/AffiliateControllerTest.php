<?php

namespace Tests\Feature\Http\Controllers;

use App\Application\AffiliateDistanceServiceInterface;
use App\Collections\AffiliateCollection;
use App\DTOs\AffiliateDTO;
use App\Http\Controllers\AffiliateController;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;
use Tests\TestCase;

class AffiliateControllerTest extends TestCase
{
    protected MockInterface $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = $this->mock(AffiliateDistanceServiceInterface::class);

        Route::get('/affiliates', [AffiliateController::class, 'index'])
            ->name('affiliates.index');
    }

    public function test_affiliate_index_shows_table_and_pagination(): void
    {
        $dto1 = new AffiliateDTO(1, 'First', 53.1, -6.1);
        $dto2 = new AffiliateDTO(2, 'Second', 53.2, -6.2);
        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection([$dto1, $dto2]));

        $response = $this->get('/affiliates?per_page=1&page=1');

        $response->assertStatus(200);
        $response->assertSeeText('First');
        $response->assertSee('table');
        $response->assertDontSee('Second');
    }

    public function test_empty_affiliates_shows_no_results_message(): void
    {
        $this->serviceMock
            ->shouldReceive('getNearbyAffiliates')
            ->once()
            ->andReturn(new AffiliateCollection([]));

        $response = $this->get('/affiliates');

        $response->assertStatus(200);
        $response->assertSeeText('No affiliates within the configured radius.');
        $response->assertDontSee('<table');
    }
}
