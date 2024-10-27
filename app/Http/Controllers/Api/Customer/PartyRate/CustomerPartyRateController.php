<?php

namespace App\Http\Controllers\Api\Customer\PartyRate;

use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyReservation;
use App\Models\Party\PartyWishlist;
use App\Models\Party\PreparationTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPartyRateController extends Controller
{


    public function show(Request $request)
    {
        $partyRate = PartyRate::where('customer_id', $request->userId)->where('party_id', $request->partyId)->value('rate')??0;



        return response()->json([
            'data' => [
                'rate' => $partyRate,
            ]
        ]);
    }



    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $data = $request->validate([
                'partyId' => 'required',
                'userId' => 'required',
                'rate' => 'required'
            ]);

            $partyRate = PartyRate::where('party_id', $data['partyId'])->where('customer_id', $data['userId'])->first();

            if(!$partyRate){
                $partyRate = PartyRate::create([
                    'party_id' => $data['partyId'],
                    'customer_id' => $data['userId'],
                    'rate' => $data['rate']
                ]);
            } else{

                $partyRate->update([
                    'rate' => $data['rate']
                ]);
            }


            DB::commit();

            return response()->json([
                'message' => 'تم التقييم بنجاح',
                'data' => [
                    'rate' => $partyRate->rate
                ]
            ]);


        }catch(Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);

        }

    }


    public function destroy($id)
    {

        PartyReservation::find($id)->delete();

        return response()->json([
            'message' => 'تم الغاء الحجز بنجاح'
        ]);
    }

}
