<?php

namespace App\Http\Controllers\Api\Customer\Slider;

use App\Http\Controllers\Controller;
use App\Models\Slider\Silder;

class CustomerSliderController extends Controller
{


    public function index()
    {
        $sliders = Silder::orderBy('order')->get(['id', 'path', 'title']);

        return response()->json([
            'data' => [
                'sliders' => $sliders
            ]
        ]);
    }

}
