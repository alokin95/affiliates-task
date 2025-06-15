<?php

namespace App\Http\Controllers\Api;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Controllers\BaseController;
use App\Http\Responses\ApiResponse;
use App\Http\Traits\PaginatesArray;
use App\Utils\TypeHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiAffiliateController extends BaseController
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): JsonResponse
    {
        $officeLat = TypeHelper::toFloatStrict(
            config('affiliates.office_lat', 0.0),
            'office_lat'
        );

        $officeLon = TypeHelper::toFloatStrict(
            config('affiliates.office_lon', 0.0),
            'office_lon'
        );

        $collection = $service->getNearbyAffiliates($officeLat, $officeLon);

        $paginator = $this->paginateArray(
            $collection->all(),
            $this->getPerPage($request),
            $this->getPage($request),
            $request->url(),
            (array) $request->query()
        );

        return ApiResponse::make(
            $paginator->items(),
            200,
            ApiResponse::fromPaginator($paginator)
        );
    }
}
