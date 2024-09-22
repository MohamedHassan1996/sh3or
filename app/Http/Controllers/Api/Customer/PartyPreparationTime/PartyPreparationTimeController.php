<?php

namespace App\Http\Controllers\Api\Customer\PartyPreparationTime;

use App\Http\Controllers\Controller;
use App\Models\Party\PreparationTime;
use Carbon\Carbon;

class PartyPreparationTimeController extends Controller
{


    public function index()
    {
        $preparationTimes = PreparationTime::all(['id', 'start_at', 'end_at']);

        $preparationTimes = $preparationTimes->map(function ($preparationTime) {
            return [
                'prepTimeId' => $preparationTime->id,
                'startAt' => Carbon::parse( $preparationTime->start_at)->format('H:i'),
                'endAt' => Carbon::parse( $preparationTime->end_at)->format('H:i'),
            ];
        });

        return response()->json([
            'data' => [
                'preparationTimes' => $preparationTimes
            ]
        ]);
    }

}
