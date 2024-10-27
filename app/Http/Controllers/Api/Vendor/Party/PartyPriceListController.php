<?php

namespace App\Http\Controllers\Api\Vendor\Party;

use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\Party\PriceList\AllPartyPriceListCollection;
use App\Http\Resources\Vendor\Party\PriceList\PartyPriceListResource;
use App\Http\Resources\Vendor\PriceList\AllPriceListCollection;
use App\Http\Resources\Vendor\PriceList\PriceListResource;
use App\Models\Party\PartyPriceList;
use App\Models\Party\PriceList;
use App\Utils\PaginateCollection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartyPriceListController extends Controller
{



    public function index(Request $request)
    {


        $partyPriceLists = PartyPriceList::with('pricelist')->where('party_id', $request->partyId)->get();

        return new AllPartyPriceListCollection(PaginateCollection::paginate(collect($partyPriceLists), $request->pageSize?$request->pageSize:10));

    }

    public function store(Request $request)
    {

        try{

            $data = $request->all();


            DB::beginTransaction();

            $user = Auth::guard('api')->user();

            if($data['type'] == PriceListType::MAIN->value){
                $priceLists = PriceList::where('vendor_id', $user->id)->pluck('id')->toArray();

                PartyPriceList::whereIn('pricelist_id', $priceLists)->update([
                    'type' => PriceListType::SECONDARY->value,
                ]);

            }

            $priceList = PartyPriceList::create([
                'party_id' => $data['partyId'],
                'pricelist_id' => $data['pricelistId'],
                'status' => PriceListStatus::from($data['status'])->value,
                'type' => PriceListType::from($data['type'])->value
            ]);

            DB::commit();

            return response()->json([
                'message' => 'تم اضافة السعر للحفل'
            ]);

        }catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);

        }
    }

    public function edit($id, Request $request)
    {

        $priceList = PartyPriceList::find($id);

        return new PartyPriceListResource($priceList);
    }


    public function update(Request $request)
    {


        try{

            $data = $request->all();

            DB::beginTransaction();

            $user = Auth::user();

            if($data['type'] == PriceListType::MAIN->value){
                $priceLists = PriceList::where('vendor_id', $user->id)->pluck('id')->toArray();
                PartyPriceList::whereIn('pricelist_id', $priceLists)->update([
                    'type' => PriceListType::SECONDARY->value,
                ]);
            }

            $priceList = PartyPriceList::find($data['partyPriceListId']);

            $priceList->update([
                'status' => PriceListStatus::from($data['status'])->value,
                'type' => PriceListType::from($data['type'])->value
            ]);

            DB::commit();

            return response()->json([
                'message' => 'تم التعديل بنجاح'
            ]);

        }catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);

        }
    }


    public function destroy($id)
    {
        $priceList = PartyPriceList::find($id);
        $priceList->delete();


        return response()->json([
            'message' => 'تم حذف السعر بنجاح'
        ]);
    }



}
