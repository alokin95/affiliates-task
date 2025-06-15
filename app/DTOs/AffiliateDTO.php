<?php

namespace App\DTOs;

final readonly class AffiliateDTO
{
    public function __construct(
        public int $affiliateId,
        public string $name,
        public float $latitude,
        public float $longitude,
    ) {
    }

    /**
     * @param array{affiliate_id: int|string, name: string, latitude: float|string, longitude: float|string} $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['affiliate_id'],
            (string) $data['name'],
            (float) $data['latitude'],
            (float) $data['longitude'],
        );
    }

    /**
     * @return array<string, int|float|string>
     */
    public function toArray(): array
    {
        return [
            'affiliateId' => $this->affiliateId,
            'name'        => $this->name,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
        ];
    }
}
