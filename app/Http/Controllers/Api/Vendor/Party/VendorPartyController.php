<?php

namespace App\Http\Controllers\Api\Vendor\Party;

use App\Enums\Party\PartyCancelStatus;
use App\Enums\Party\PartyStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\Party\AllVendorPartyCollection;
use App\Models\Party\Party;
use App\Models\Party\PartyFacility;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyMedia;
use App\Models\Party\PartyPreparationTime;
use App\Models\Party\PartyWishlist;
use App\Services\Upload\UploadService;
use App\Utils\PaginateCollection;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPartyController extends Controller
{

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }



    public function index(Request $request)
    {
        //$user = Auth::guard('api')->user();


        $partiesData = DB::table('parties')
            ->join('party_categories', 'parties.category_id', '=', 'party_categories.id')
            //->join('party_preparation_times', 'parties.id', '=', 'party_preparation_times.party_id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->leftJoin('party_media', function ($join) {
                $join->on('parties.id', '=', 'party_media.party_id')
                    ->where('party_media.type', 0);
            })
            ->select(
                'parties.id as partyId',
                'parties.name as partyName',
                'cities.name as cityName',
                DB::raw('MIN(party_media.path) as partImage') // Get one record for party_media
                // Get one record for party_media
            )
            //->where('cities.id', $request->cityId)
            ->where('cities.status', 1)
            //->where('party_categories.id', $request->categoryId)
            ->where('party_categories.status', 1)
            //->where('party_preparation_times.preparation_time_id', $request->preparationTimeId)
            //->where('party_preparation_times.status', 1)
            //->where('parties.status', 1)
            ->where('parties.vendor_id', $request->vendorId)
            ->when($request->status !== null, function($q) use ($request) {
                return $q->where('parties.status', $request->status);
            })
            ->groupBy('parties.id', 'parties.name', 'cities.name') // Group by party details
            ->get();

        $parties = [];

        foreach ($partiesData as $party) {
            $averageRate = PartyRate::where('party_id', $party->partyId)
            ->avg('rate');
            //$price = Party::find($party->partyId)->activePrice();
            $parties[] = [
                'partyId' => $party->partyId,
                'partyName' => $party->partyName,
                'cityName' => $party->cityName,
                //'price' => $price,
                'partyImage' => $party->partImage,
                'rate' => round($averageRate, 2),
            ];
        }



        return new AllVendorPartyCollection(PaginateCollection::paginate(collect($parties), $request->pageSize?$request->pageSize:10));

    }

    public function show($id, Request $request)
    {

        $user = Auth::guard('api')->user();

        $partyData = DB::table('parties')
            ->join('party_categories', 'parties.category_id', '=', 'party_categories.id')
            //->join('party_preparation_times', 'parties.id', '=', 'party_preparation_times.party_id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->select(
                'parties.id as partyId',
                'parties.name as partyName',
                'parties.description as partyDescription',
                'cities.name as cityName',
                'cities.id as cityId',
                'party_categories.id as categoryId',
                'parties.allow_cancel as allowCancel'
            )
            ->where('cities.status', 1)
            ->where('party_categories.status', 1)
            //->where('party_preparation_times.preparation_time_id', $request->preparationTimeId)
            //->where('party_preparation_times.status', 1)
            ->where('parties.status', 1)
            ->where('parties.id', $id)
            ->first();

        $averageRate = PartyRate::where('party_id', $partyData->partyId)
            ->avg('rate');
        $totalRate =  PartyRate::where('party_id', $partyData->partyId)
        ->count();

        $inWishlist = PartyWishlist::where('party_id', $partyData->partyId)->where('customer_id', $user->id)->exists();
        $price = Party::find($partyData->partyId)->activePrice();

        $partyPrepTimesData = Db::table('party_preparation_times')
            ->join('preparation_times', 'preparation_times.id', '=', 'party_preparation_times.preparation_time_id')
            ->select(
                'party_preparation_times.id as partyPrepTimeId',
                'preparation_times.start_at as startAt',
                'preparation_times.end_at as endAt',
                'party_preparation_times.status',
            )
            ->where('party_preparation_times.party_id', $partyData->partyId)
            ->get();


        $prepTimes = [];

        foreach ($partyPrepTimesData as $partyPrepTimeData) {
            $prepTimes[] = [
                'partyPrepTimeId' => $partyPrepTimeData->partyPrepTimeId,
                'status' => $partyPrepTimeData->status
            ];
        }

        $party = [
            'partyId' => $partyData->partyId,
            'partyName' => $partyData->partyName,
            'partyDescription' => $partyData->partyDescription,
            'categoryId' => $partyData->categoryId,
            'cityId' => $partyData->cityId,
            'price' => $price,
            'rate' => round($averageRate, 2),
            'totalRates' =>$totalRate,
            'allowCancel' => $partyData->allowCancel,
            'preparationTimes' => $prepTimes
        ];

        $mediaData = PartyMedia::where('party_id', $partyData->partyId)->get();

        $partyFacilitesData = Db::table('party_facilities')
            ->join('facilities', 'facilities.id', '=', 'party_facilities.facility_id')
            ->select(
                'party_facilities.facility_id as facilityId',
                'facilities.name as facilityName',
                'facilities.path as facilityImage'
            )
            ->where('party_facilities.party_id', $partyData->partyId)
            ->where('party_facilities.status', 1)
            ->where('facilities.status', 1)
            ->get();


        $media = [];
        $partyFacilites = [];


        foreach ($partyFacilitesData as $key => $record) {
            $partyFacilites[] = [
                'facilityId' => $record->facilityId,
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

    public function store(Request $request)
    {
        $data = $request->all();
        $partyFacilitiesData = $data['facilities'];
        $partyMediaData = $data['media'];
        $partypreparationTimesData = $data['preparationTimes'];

        try{

            DB::beginTransaction();

            $party = Party::create([
                'name' => $data['partyName'],
                'description' => $data['partyDescription'],
                'category_id' => $data['categoryId'],
                'city_id' => $data['cityId'],
                'allow_cancel' => PartyCancelStatus::from($data['allowCancel'])->value,
                'status' => PartyStatus::REVIEW->value,
                'vendor_id' => $data['vendorId']
            ]);


            foreach ($partyMediaData as $key => $record) {

                $mediaPath =  $this->uploadService->uploadFile($record['media'], 'parties', 'dashboard_storage');

                PartyMedia::create([
                    'party_id' => $party->id,
                    'path' => $mediaPath
                ]);
            }


            foreach ($partyFacilitiesData as $key => $record) {
                PartyFacility::create([
                    'party_id' => $party->id,
                    'facility_id' => $record['facilityId'],
                    'status' => 1
                ]);
            }


            foreach ($partypreparationTimesData as $key => $record) {
                $partyPreparationTime = PartyPreparationTime::create([
                    'party_id' => $party->id,
                    'preparation_time_id' => $record['preparationTimeId'],
                    'status' => 1
                ]);
            }


            DB::commit();

            return response()->json([
                'message' => 'تم اضافة الحفلة انتظر المراجعة'
            ]);

        }catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);

        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'partyId' => 'required',
            'partyName' => 'required',
            'partyDescription' => 'required',
            'categoryId' => 'required',
            'cityId' => 'required',
            'allowCancel' => 'required',
        ]);

        $party = Party::find($request->partyId);

        $party->update([
            'name' => $data['partyName'],
            'description' => $data['partyDescription'],
            'category_id' => $data['categoryId'],
            'city_id' => $data['cityId'],
            'allow_cancel' => PartyCancelStatus::from($data['allowCancel'])->value
        ]);

        return response()->json([
            'message' => 'تم تعديل الحفلة بنجاح'
        ]);
    }

}
