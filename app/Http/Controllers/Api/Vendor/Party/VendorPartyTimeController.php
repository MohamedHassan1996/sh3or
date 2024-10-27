<?php

namespace App\Http\Controllers\Api\Vendor\Party;

use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyFacility;
use App\Models\Party\PartyPreparationTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorPartyTimeController extends Controller
{


    public function index(Request $request)
    {
        $partyPrepTimesData = Db::table('party_preparation_times')
            ->join('preparation_times', 'preparation_times.id', '=', 'party_preparation_times.preparation_time_id')
            ->select(
                'party_preparation_times.id as partyPrepTimeId',
                'party_preparation_times.preparation_time_id as prepTimeId',
                'preparation_times.start_at as startAt',
                'preparation_times.end_at as endAt',
                'party_preparation_times.status',
            )
            ->where('party_preparation_times.party_id', $request->partyId)
            ->get();


        $prepTimes = [];

        foreach ($partyPrepTimesData as $partyPrepTimeData) {
            $prepTimes[] = [
                'partyPrepTimeId' => $partyPrepTimeData->partyPrepTimeId,
                'prepTimeId' => $partyPrepTimeData->prepTimeId,
                'prepTime' => Carbon::parse($partyPrepTimeData->startAt)->format('H:i') . ' - ' . Carbon::parse($partyPrepTimeData->endAt)->format('H:i'),
                'status' => $partyPrepTimeData->status
            ];
        }



        return response()->json([
            'data' => $prepTimes
        ]);

    }

    public function store(Request $request)
    {
        try{


            $prepTimesData = $request->all()['partyPrepartionTimes'];

            DB::beginTransaction();

            if(!empty($prepTimesData)){
                $prepTimes = PartyPreparationTime::where('party_id', $request->partyId)->delete();
            }

            foreach ($prepTimesData as $key => $prepTimeData) {
                $prepTime = PartyPreparationTime::create([
                    'party_id' => $request->partyId,
                    'preparation_time_id' => $prepTimeData['preparationTimeId'],
                    'status' => 1
                ]);
            }


            DB::commit();


            return response()->json([
                'message' => 'تم التحديث بنجاح'
            ]);


        }catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);

        }


    }

    /*public function show($id, Request $request)
    {

        $partyFacilityData = Db::table('party_facilites')
        ->join('facilities', 'facilities.id', '=', 'party_facilites.facility_id')
        ->select(
            'party_facilites.id as partyFacilityId',
            'party_facilites.facility_id as facilityId',
            'party_facilites.status as status',
            'party_facilites.party_id as partyId',
            'facilities.name as facilityName',
            'facilities.path as facilityImage'
        )
        ->where('party_facilites.id', $id)
        ->first();

        //dd()


            $facilities[] = [
                'partyFacilityId' => $partyFacilityData->partyId,
                'facilityId' => $partyFacilityData->facilityId,
                'facilityImage' => $partyFacilityData->facilityImage,
                'facilityName' => $partyFacilityData->facilityName,
                'status' => $partyFacilityData->status
            ];



        return response()->json([
            'data' => $facilities
        ]);
    }

    public function update(Request $request)
    {

        try{
            $data = $request->validate([
                'partyFacilityId' => 'required',
                'facilityId' => 'required',
                'status' => 'required'
            ]);

            DB::beginTransaction();

            $partyFacility = PartyFacility::find($data['partyFacilityId']);


            $partyFacility->update([
                'status' => $data['status'],
                'facility_id' => $data['facilityId']
            ]);


            DB::commit();

            return response()->json([
                'message' => 'تم تعديل المرفق بنجاح'
            ]);

        }catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);

        }

    }

    public function destroy($id){

        PartyFacility::find($id)->delete();

        return response()->json([
            'message' => 'تم الحذف بنجاح'
        ]);
    }*/


}
