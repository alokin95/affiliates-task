<?php

namespace App\Http\Controllers\Api;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Traits\PaginatesArray;
use App\Utils\TypeHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiAffiliateController extends Controller
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): JsonResponse
    {
        $perPage = TypeHelper::toIntOrDefault(
            $request->input('per_page', config('affiliates.per_page', 10)),
            10
        );

        $page = TypeHelper::toIntOrDefault(
            $request->input('page', config('affiliates.page', 1)),
            1
        );

        $officeLat = TypeHelper::toFloatStrict(
            config('affiliates.office_lat', 0.0),
            'office_lat'
        );

        $officeLon = TypeHelper::toFloatStrict(
            config('affiliates.office_lon', 0.0),
            'office_lon'
        );

        $collection = $service->getNearbyAffiliates(
            config('affiliates.office_lat'),
            config('affiliates.office_lon')
        );

        $paginator = $this->paginateArray(
            $collection->all(),
            $perPage,
            $page,
            $request->url(),
            $request->query()
        );

        return ApiResponse::make(
            $paginator->items(),
            200,
            ApiResponse::fromPaginator($paginator)
        );
    }
}
