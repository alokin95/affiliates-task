<?php

namespace App\Http\Controllers;

use App\Utils\TypeHelper;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function getPerPage(Request $request): int
    {
        return TypeHelper::toIntOrDefault(
            $request->input('per_page', config('affiliates.per_page', 10)),
            10
        );
    }

    protected function getPage(Request $request): int
    {
        return TypeHelper::toIntOrDefault(
            $request->input('page', config('affiliates.page', 1)),
            1
        );
    }
}
