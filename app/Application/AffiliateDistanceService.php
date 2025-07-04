<?php

namespace App\Application;

use App\Collections\AffiliateCollection;
use App\Constants\Earth;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;

readonly class AffiliateDistanceService implements AffiliateDistanceServiceInterface
{
    public function __construct(
        private AffiliateSourceInterface $affiliateSource
    ) {
    }

    /**
     * @return AffiliateCollection<AffiliateDTO>
     */
    public function getNearbyAffiliates(
        float $officeLatitude,
        float $officeLongitude,
        float $radiusKm = 100
    ): AffiliateCollection {
        $allAffiliates = $this->affiliateSource->getAll();

        $matching = $allAffiliates
            ->filter(fn (AffiliateDTO $a) =>
                $this->haversineDistance(
                    $a->latitude,
                    $a->longitude,
                    $officeLatitude,
                    $officeLongitude
                ) <= $radiusKm
            )
            ->sort(fn (AffiliateDTO $a, AffiliateDTO $b): int => $a->affiliateId <=> $b->affiliateId);

        return new AffiliateCollection($matching->all());
    }


    private function haversineDistance(
        float $sourceLatitude,
        float $sourceLongitude,
        float $destinationLatitude,
        float $destinationLongitude
    ): float {
        $radius = Earth::RADIUS_KM;

        $lat1     = deg2rad($sourceLatitude);
        $lat2     = deg2rad($destinationLatitude);
        $deltaLat = $lat2 - $lat1;

        $lon1     = deg2rad($sourceLongitude);
        $lon2     = deg2rad($destinationLongitude);
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2)                            ** 2
            + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;

        $centralAngle = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radius * $centralAngle;
    }
}
