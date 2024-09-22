<?php

namespace App\Http\Controllers\Api\Customer\City;

use App\Http\Controllers\Controller;
use App\Models\City\City;

class CustomerCityController extends Controller
{


    public function index()
    {
        $cities = City::all(['id', 'name', 'path']);

        return response()->json([
            'data' => [
                'cities' => $cities
            ]
        ]);
    }

}
