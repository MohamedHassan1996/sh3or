<?php

namespace App\Http\Controllers\Api\Customer\Stats;

use App\Http\Controllers\Controller;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyReservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerStatsController extends Controller
{


    public function show(Request $request)
    {
        $totalReservations = PartyReservation::where('customer_id', $request->userId)->count();

        return response()->json([
            'data' => [
                'totalReservations' => $totalReservations,
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

            dd($data);

            PartyRate::updateOrCreate(
                [
                    'customer_id' => $data['userId'],  // Criteria for finding the record
                    'part_id' => $data['partyId'],
                ],
                [
                    'rate' => $data['rate']  // Values to update or create with
                ]
            );


            DB::commit();

            return response()->json([
                'message' => 'تم التقييم بنجاح'
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
