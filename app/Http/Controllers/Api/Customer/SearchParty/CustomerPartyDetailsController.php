<?php

namespace App\Http\Controllers\Api\Customer\SearchParty;

use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyMedia;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyWishlist;
use App\Models\Slider\Silder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPartyDetailsController extends Controller
{


    public function show($id, Request $request)
    {

        $user = Auth::guard('api')->user();

        $partyData = DB::table('parties')
            ->join('party_categories', 'parties.catgory_id', '=', 'party_categories.id')
            ->join('party_preparation_times', 'parties.id', '=', 'party_preparation_times.party_id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->select(
                'parties.id as partyId',
                'parties.name as partyName',
                'parties.description as partyDescription',
                'cities.name as cityName',
                'parties.allow_cancel as allowCancel'
            )
            ->where('cities.id', $request->cityId)
            ->where('cities.status', 1)
            ->where('party_categories.id', $request->categoryId)
            ->where('party_categories.status', 1)
            ->where('party_preparation_times.preparation_time_id', $request->preparationTimeId)
            ->where('party_preparation_times.status', 1)
            ->where('parties.status', 1)
            ->where('parties.id', $id)
            ->first();

        $averageRate = PartyRate::where('party_id', $partyData->partyId)
            ->avg('rate');
        $inWishlist = PartyWishlist::where('party_id', $partyData->partyId)->where('customer_id', $user->id)->exists();
        $price = Party::find($partyData->partyId)->activePrice();

        $party = [
            'partyId' => $partyData->partyId,
            'partyName' => $partyData->partyName,
            'partyDescription' => $partyData->partyDescription,
            'cityName' => $partyData->cityName,
            'price' => $price,
            'rate' => round($averageRate, 2),
            'inWishlist' => $inWishlist == true?1:0,
            'allowCancel' => $partyData->allowCancel
        ];

        $mediaData = PartyMedia::where('party_id', $partyData->partyId)->get();

        $partyFacilitesData = Db::table('party_facilites')
            ->join('facilities', 'facilities.id', '=', 'party_facilites.facility_id')
            ->select(
                'facilities.name as facilityName',
                'facilities.path as facilityImage'
            )
            ->where('party_facilites.party_id', $partyData->partyId)
            ->where('party_facilites.status', 1)
            ->where('facilities.status', 1)
            ->get();


        $media = [];
        $partyFacilites = [];


        foreach ($partyFacilitesData as $key => $record) {
            $partyFacilites[] = [
                'facilityName' => $record->facilityName,
                'facilityPath' => $record->facilityImage
            ];
        }

        foreach ($mediaData as $key => $record) {
            $media[] = [
                'mediaId' => $record->id,
                'mediaPath' => $record->path,
            ];
        }


        $party['media'] = $media;
        $party['facilities'] = $partyFacilites;



        return response()->json([
            'data' => [
                'party' => $party
            ]
        ]);
    }
}
