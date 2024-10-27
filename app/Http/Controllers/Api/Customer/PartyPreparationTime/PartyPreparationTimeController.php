<?php

namespace App\Http\Controllers\Api\Customer\PartyPreparationTime;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartyPreparationTime\AllPartyPreparationTimeCollection;
use App\Models\Party\PreparationTime;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;

class PartyPreparationTimeController extends Controller
{


    public function index(Request $request)
    {
        $preparationTimes = PreparationTime::all(['id', 'start_at', 'end_at']);

        return response()->json(new AllPartyPreparationTimeCollection(PaginateCollection::paginate($preparationTimes, $request->pageSize?$request->pageSize:10)));

    }

}
