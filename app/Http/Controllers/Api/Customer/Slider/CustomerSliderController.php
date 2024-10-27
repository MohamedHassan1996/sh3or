<?php

namespace App\Http\Controllers\Api\Customer\Slider;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\Slider\AllSliderCollection;
use App\Models\Slider\Silder;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class CustomerSliderController extends Controller
{


    public function index(Request $request)
    {
        $sliders = Silder::orderBy('id', 'desc')->get(['id', 'path', 'title']);

        return response()->json(

            new AllSliderCollection(PaginateCollection::paginate($sliders, $request->pageSize?$request->pageSize:10))

        );


    }

}
