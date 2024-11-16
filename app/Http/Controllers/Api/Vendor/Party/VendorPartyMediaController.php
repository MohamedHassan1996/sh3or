<?php

namespace App\Http\Controllers\Api\Vendor\Party;

use App\Http\Controllers\Controller;
use App\Models\Party\PartyMedia;
use App\Services\Upload\UploadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorPartyMediaController extends Controller
{

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }



    public function index(Request $request)
    {
        $partyMedia = PartyMedia::select([
            'id as mediaId',  // Alias id to mediaId
            'path as mediaPath'  // Alias path to mediaPath
        ])->where('party_id', $request->partyId)->get();



        return response()->json([
            'data' => $partyMedia
        ]);

    }

    public function store(Request $request)
    {
        try{


            DB::commit();

            $data = $request->all();

            $mediaPath = null;

            if(isset($data['media']) && $data['media'] instanceof UploadedFile){
                $mediaPath =  $this->uploadService->uploadFile($data['media'], 'parties', 'dashboard_storage');
            }


            $partyMedia = PartyMedia::create([
                'party_id' => $request->partyId,
                'path' => $mediaPath
            ]);

            DB::commit();

            return response()->json([
                'message' => 'تم رفع الملف بنجاح'
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

    }*/

    public function destroy($id){

        $partyMedia = PartyMedia::find($id);

        if ($partyMedia) {
            // Delete the file from storage
            Storage::delete($partyMedia->path);

            // Delete the record from the database
            $partyMedia->delete();

            return response()->json(['message' => 'Media deleted successfully.'], 200);
        }

        return response()->json(['error' => 'Media not found.'], 401);

    }
}
