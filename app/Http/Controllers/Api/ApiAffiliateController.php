<?php

namespace App\Http\Controllers\Api;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Traits\PaginatesArray;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiAffiliateController extends Controller
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): JsonResponse
    {
        $perPage = (int) $request->input('per_page', config('affiliates.per_page', 10));
        $page = (int) $request->input('page', 1);

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
