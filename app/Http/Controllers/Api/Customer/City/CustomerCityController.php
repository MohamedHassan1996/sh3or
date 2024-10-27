<?php

namespace App\Http\Controllers\Api\Customer\City;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\AllCityCollection;
use App\Models\City\City;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class CustomerCityController extends Controller
{


    public function index(Request $request)
    {
        $cities = City::all(['id', 'name', 'path']);

        return response()->json(new AllCityCollection(PaginateCollection::paginate($cities, $request->pageSize?$request->pageSize:10))
    );
    }

}
