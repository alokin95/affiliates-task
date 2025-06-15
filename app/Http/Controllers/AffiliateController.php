<?php

namespace App\Http\Controllers;

use App\Application\AffiliateDistanceServiceInterface;
use App\Http\Traits\PaginatesArray;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    use PaginatesArray;

    public function index(Request $request, AffiliateDistanceServiceInterface $service): View
    {
        $perPage = (int) $request->input('per_page', config('affiliates.per_page', 10));
        $page    = (int) $request->input('page', 1);

        $collection = $service->getNearbyAffiliates(
            config('affiliates.office_lat'),
            config('affiliates.office_lon'),
        );

        $paginator = $this->paginateArray(
            $collection->all(),
            $perPage,
            $page,
            $request->url(),
            $request->query()
        );

        return view('affiliates.index', compact('paginator'));
    }
}
