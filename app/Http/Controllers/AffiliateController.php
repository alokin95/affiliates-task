<?php

namespace App\Http\Controllers;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Traits\PaginatesArray;
use App\Utils\TypeHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AffiliateController extends BaseController
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): View
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
            TypeHelper::toStringStrict($request->url(), 'request URL'),
            (array) $request->query()
        );

        return view('affiliates.index', compact('paginator'));
    }
}
