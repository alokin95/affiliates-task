<?php

namespace App\Application;

use App\Collections\AffiliateCollection;
use App\Constants\Earth;
use App\Domain\Affiliates\AffiliateSourceInterface;
use App\DTOs\AffiliateDTO;

readonly class AffiliateDistanceService
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

        $matching = array_filter(
            $allAffiliates,
            fn (AffiliateDTO $affiliate) => $this->haversineDistance(
                sourceLatitude: $affiliate->latitude,
                sourceLongitude: $affiliate->longitude,
                destinationLatitude: $officeLatitude,
                destinationLongitude: $officeLongitude
            ) <= $radiusKm
        );

        usort(
            $matching,
            fn (AffiliateDTO $a, AffiliateDTO $b) => $a->affiliateId <=> $b->affiliateId
        );

        return new AffiliateCollection($matching);
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
