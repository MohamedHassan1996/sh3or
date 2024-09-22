<?php

namespace App\Http\Controllers\Api\Customer\SearchParty;

use App\Http\Controllers\Controller;
use App\Models\Slider\Silder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSearchPartyController extends Controller
{


    public function index(Request $request)
    {
        $parties = DB::table('parties')
            ->join('party_categories', 'parties.catgory_id', '=', 'party_categories.id')
            ->join('party_preparation_times', 'parties.id', '=', 'party_preparation_times.party_id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->join('party_price_lists', 'party_price_lists.party_id', '=', 'parties.id')
            ->join('price_lists', 'party_price_lists.pricelist_id', '=', 'price_lists.id')
            ->select('parties.id as partyId', 'parties.name as partyName', 'cities.name as cityName', 'price_lists.price')
            ->where('cities.id', $request->cityId)
            ->where('cities.status', 1)
            ->where('party_categories.id', $request->categoryId)
            ->where('party_categories.status', 1)
            ->where('party_preparation_times.preparation_time_id', $request->preparationTimeId)
            ->where('party_preparation_times.status', 1)
            ->where('parties.status', 1)
            ->get();


        return response()->json([
            'data' => [
                'parties' => $parties
            ]
        ]);
    }

}
