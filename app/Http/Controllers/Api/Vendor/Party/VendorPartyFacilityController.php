<?php

namespace App\Http\Controllers\Api\Vendor\Party;

use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyFacility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorPartyFacilityController extends Controller
{


    public function index(Request $request)
    {
        $partyFacilitesData = Db::table('party_facilities')
            ->join('facilities', 'facilities.id', '=', 'party_facilities.facility_id')
            ->select(
                'party_facilities.id as partyFacilityId',
                'party_facilities.facility_id as facilityId',
                'party_facilities.status as status',
                'party_facilities.party_id as partyId',
                'facilities.name as facilityName',
                'facilities.path as facilityImage'
            )
            ->where('party_facilities.party_id', $request->partyId)
            ->get();

            //dd()

        $facilities = [];

        foreach ($partyFacilitesData as $partyFacilityData) {
            $facilities[] = [
                'partyFacilityId' => $partyFacilityData->partyFacilityId,
                'facilityId' => $partyFacilityData->facilityId,
                'facilityImage' => $partyFacilityData->facilityImage,
                'facilityName' => $partyFacilityData->facilityName,
                'status' => $partyFacilityData->status
            ];
        }



        return response()->json([
            'data' => $facilities
        ]);

    }

    public function store(Request $request)
    {
        try{


            /*$data = $request->validate([
                'partyId' => 'required',
                'facilityId' => 'required',
                'status' => 'required'
            ]);

            DB::beginTransaction();

            $partyFacility = PartyFacility::create([
                'party_id' => $data['partyId'],
                'facility_id' => $data['facilityId'],
                'status' => $data['status']
            ]);


            DB::commit();

            return response()->json([
                'message' => 'تم اضافة المرفق بنجاح'
            ]);*/
            $facilitiesData = $request->all()['partyFacilities'];

            DB::beginTransaction();

            if(!empty($facilitiesData)){
                $partyFacilities = PartyFacility::where('party_id', $request->partyId)->delete();
            }

            foreach ($facilitiesData as $key => $facilityData) {
                $partyFacility = PartyFacility::create([
                    'party_id' => $request->partyId,
                    'facility_id' => $facilityData['facilityId'],
                    'status' => 1
                ]);
            }

            DB::commit();


            return response()->json([
                'message' => 'تم تعديل المرفقات بنجاح'
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
                'partyFacilityId' => $partyFacilityData->partyFacilityId,
                'facilityId' => $partyFacilityData->facilityId,
                'facilityImage' => $partyFacilityData->facilityImage,
                'facilityName' => $partyFacilityData->facilityName,
                'status' => $partyFacilityData->status
            ];



        return response()->json([
            'data' => $facilities
        ]);
    }*/

    /*public function update(Request $request)
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

    }*/

   /* public function destroy($id){

        PartyFacility::find($id)->delete();

        return response()->json([
            'message' => 'تم الحذف بنجاح'
        ]);
    }*/


}
