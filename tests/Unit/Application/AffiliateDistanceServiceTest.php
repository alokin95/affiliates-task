<?php

namespace Unit\Application;

use App\Application\AffiliateDistanceService;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AffiliateDistanceServiceTest extends TestCase
{
    private const float OFFICE_LAT        = 53.3340285;
    private const float OFFICE_LON        = -6.2535495;
    private const float DEFAULT_RADIUS_KM = 100.0;

    /**
     * @throws Exception
     */
    private function createServiceWith(array $dtos): AffiliateDistanceService
    {
        $mock = $this->createMock(AffiliateSourceInterface::class);
        $mock->method('getAll')->willReturn($dtos);

        return new AffiliateDistanceService($mock);
    }

    /**
     * @throws Exception
     */
    public function test_returns_empty_collection_when_no_affiliates(): void
    {
        $service = $this->createServiceWith([]);
        $result  = $service->getNearbyAffiliates(
            self::OFFICE_LAT,
            self::OFFICE_LON,
            self::DEFAULT_RADIUS_KM
        );

        $this->assertCount(0, $result);
    }

    /**
     * @throws Exception
     */
    public function test_includes_affiliates_within_radius_sorted_by_id(): void
    {
        $inside1 = new AffiliateDTO(10, 'Inside1', self::OFFICE_LAT + 0.001, self::OFFICE_LON - 0.001);
        $inside2 = new AffiliateDTO(5, 'Inside2', self::OFFICE_LAT + 0.002, self::OFFICE_LON + 0.001);
        $outside = new AffiliateDTO(20, 'Outside', 52.0, -7.0);

        $service = $this->createServiceWith([$outside, $inside1, $inside2]);
        $result  = $service->getNearbyAffiliates(self::OFFICE_LAT, self::OFFICE_LON, 10.0);

        $ids = array_map(fn ($dto) => $dto->affiliateId, $result->all());
        $this->assertEquals([5, 10], $ids);
    }

    /**
     * @throws Exception
     */
    public function test_affiliates_sorted_by_affiliate_id(): void
    {
        $a3 = new AffiliateDTO(3, 'Three', self::OFFICE_LAT + 0.001, self::OFFICE_LON);
        $a1 = new AffiliateDTO(1, 'One', self::OFFICE_LAT + 0.001, self::OFFICE_LON);
        $a2 = new AffiliateDTO(2, 'Two', self::OFFICE_LAT + 0.001, self::OFFICE_LON);

        $service = $this->createServiceWith([$a3, $a1, $a2]);
        $result  = $service->getNearbyAffiliates(self::OFFICE_LAT, self::OFFICE_LON, 5.0);

        $ids = array_map(fn ($dto) => $dto->affiliateId, $result->all());
        $this->assertEquals([1, 2, 3], $ids);
    }
}
