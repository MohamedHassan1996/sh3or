<?php

namespace App\Http\Controllers\Api\Vendor\PriceList;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\PriceList\AllPriceListCollection;
use App\Http\Resources\Vendor\PriceList\PriceListResource;
use App\Models\Party\PriceList;
use App\Utils\PaginateCollection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceListController extends Controller
{



    public function index(Request $request)
    {


        $priceLists = PriceList::where('vendor_id', $request->vendorId)->get();

        return new AllPriceListCollection(PaginateCollection::paginate(collect($priceLists), $request->pageSize?$request->pageSize:10));

    }

    public function store(Request $request)
    {
        $data = $request->all();

        try{

            DB::beginTransaction();

            $priceList = PriceList::create([
                'price' => $data['price'],
                'name' => $data['name'],
                'start_at' => $data['startAt'],
                'end_at' => $data['endAt'],
                'vendor_id' => $data['vendorId'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'تم اضافة سعر جديد'
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

        $priceList = PriceList::find($id);

        return new PriceListResource($priceList);
    }


    public function update(Request $request)
    {
        $data = $request->all();

        $priceList = PriceList::find($request->priceListId);
        $priceList->update([
            'price' => $data['price'],
            'name' => $data['name'],
            'start_at' => $data['startAt'],
            'end_at' => $data['endAt'],
        ]);


        return response()->json([
            'message' => 'تم تعديل السعر بنجاح'
        ]);
    }


    public function destroy($id)
    {
        $priceList = PriceList::find($id);
        $priceList->delete();


        return response()->json([
            'message' => 'تم حذف السعر بنجاح'
        ]);
    }



}
