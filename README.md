Setup & Usage

git clone

cd project

composer install

copy .env.example to .env

change dataase credentials

php artisan key:generate

copy affiliates.txt to storage/app/private

php artisan serve

Visit:

    http://localhost:8000/affiliates

    http://localhost:8000/api/affiliates


Overview

A Laravel-based project that:

    Parses affiliates.txt (JSON per line)

    Calculates distance from Dublin office (53.3340285, -6.2535495) using the great-circle formula

    Filters affiliates within 100 km and sorts by ID (ascending)

    Outputs results via web and API endpoints

Key Decisions
Office Coordinates via IoC

Office latitude and longitude are injected through configuration (.env → config/affiliates.php) via Laravel’s IoC container.
They aren't declared as const in code, because using class constants would hardcode values at compile-time, preventing easy override across environments.
Class Constant vs Enum (Earth Radius)

I used a simple PHP class constant for Earth’s radius (EARTH_RADIUS_KM) instead of a PHP 8.1 enum because only one scalar value is needed—no enum behavior (methods, exhaustive cases) is required.

Static Analysis & Code Style

    PHPStan level 9 ensures strict type safety, generic correctness, and inline documentation.

    php-cs-fixer enforces consistent code style and PSR-12 compliance.

Architecture

    Infrastructure: Reads and parses the raw TXT file via FileAffiliateSource

    Domain/Application: AffiliateDistanceService, DTOs, collection pipeline (filter, sort)

    Controllers: Web and API controllers use service layer and return HTML or JSON

    Utils: Safety helpers (TypeHelper) ensure strong runtime type integrity

.env / config/affiliates.php

AFFILIATES_FILE_PATH='affiliates.txt'

AFFILIATES_OFFICE_LAT=53.3340285

AFFILIATES_OFFICE_LON=-6.2535495

AFFILIATES_PER_PAGE=10

Endpoints

    GET /affiliates – paginated web view

    GET /api/affiliates – JSON response (payload, pagination, status, etc.)
