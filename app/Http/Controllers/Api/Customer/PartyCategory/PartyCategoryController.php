<?php

namespace App\Http\Controllers\Api\Customer\PartyCategory;

use App\Http\Controllers\Controller;
use App\Models\City\City;
use App\Models\Party\PartyCategory;

class PartyCategoryController extends Controller
{


    public function index()
    {
        $partyCategories = PartyCategory::all(['id', 'name', 'path']);

        return response()->json([
            'data' => [
                'partyCategories' => $partyCategories
            ]
        ]);
    }

}
