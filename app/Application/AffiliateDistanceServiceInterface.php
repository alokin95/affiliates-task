<?php

namespace App\Application;

use App\Collections\AffiliateCollection;

interface AffiliateDistanceServiceInterface
{
    public function getNearbyAffiliates(float $officeLatitude, float $officeLongitude, float $radiusKm = 100): AffiliateCollection;
}
