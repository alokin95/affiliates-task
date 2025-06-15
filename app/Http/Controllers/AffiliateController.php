<?php

namespace App\Http\Controllers;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Traits\PaginatesArray;
use App\Utils\TypeHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): View
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

        $collection = $service->getNearbyAffiliates($officeLat, $officeLon);

        $paginator = $this->paginateArray(
            $collection->all(),
            $perPage,
            $page,
            TypeHelper::toStringStrict($request->url(), 'request URL'),
            (array) $request->query()
        );

        return view('affiliates.index', compact('paginator'));
    }
}
