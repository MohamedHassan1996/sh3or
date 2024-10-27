<?php

namespace App\Http\Controllers\Api\Customer\PartyWishlist;

use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartyWishlistController extends Controller
{


    public function index(Request $request)
    {
        //$user = Auth::guard('api')->user();

        $partiesData = DB::table('party_wishlists')
            ->join('parties', 'parties.id', '=', 'party_wishlists.party_id')
            ->join('party_categories', 'parties.category_id', '=', 'party_categories.id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->leftJoin('party_media', function ($join) {
                $join->on('parties.id', '=', 'party_media.party_id')
                    ->where('party_media.type', 0);
            })
            ->select(
                'party_wishlists.id as wishlistId',
                'parties.id as partyId',
                'parties.name as partyName',
                'cities.name as cityName',
                DB::raw('MIN(party_media.path) as partImage') // Get one record for party_media
                // Get one record for party_media
            )
            ->where('cities.status', 1)
            ->where('party_categories.status', 1)
            ->where('parties.status', 1)
            ->where('party_wishlists.customer_id', $request->userId)
            ->groupBy('parties.id', 'parties.name', 'cities.name', 'party_wishlists.id') // Group by party details
            ->get();

        $parties = [];

        foreach ($partiesData as $party) {
            $averageRate = PartyRate::where('party_id', $party->partyId)
            ->avg('rate');
            $price = Party::find($party->partyId)->activePrice();
            $parties[] = [
                'wishlistId' => $party->wishlistId,
                'partyId' => $party->partyId,
                'partyName' => $party->partyName,
                'cityName' => $party->cityName,
                'price' => $price,
                'partyImage' => $party->partImage??"",
                'rate' => round($averageRate, 2),
            ];
        }



        return response()->json([
            'data' => [
                'myWishlist' => $parties
            ]
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'partyId' => 'required',
            'userId' => 'required'
        ]);

        $wishlist = PartyWishlist::where('party_id', $data['partyId']);


        if ($wishlist->where('customer_id', $data['userId'])->exists()) {

            return response()->json([
                'message' => 'مضافة للمفضلة من قبل'
            ]);
        }


        PartyWishlist::create([
            'party_id' => $data['partyId'],
            'customer_id' => $data['userId'],
        ]);


        return response()->json([
            'message' => 'تم الاضافة للمفضلة'
        ]);
    }


    public function destroy($id)
    {

        PartyWishlist::find($id)->delete();

        return response()->json([
            'message' => 'تم الحذف من المفضلة'
        ]);
    }

}
