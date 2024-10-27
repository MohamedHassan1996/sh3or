<?php

namespace App\Http\Controllers\Api\Vendor\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{


    public function index(Request $request)
    {
        $FacilitiesData = Facility::where('status', 1)->get();

        $facilities = [];

        foreach ($FacilitiesData as $facilityData) {
            $facilities[] = [
                'facilityId' => $facilityData->id,
                'facilityImage' => $facilityData->path,
                'facilityName' => $facilityData->name,
            ];
        }

        return response()->json([
            'data' => $facilities
        ]);

    }


}
