<?php

namespace App\Http\Controllers\Api\Customer\Complaint;

use App\Enums\Complaint\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\Complaint\AllComplaintCollection;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintMessage;
use App\Models\Party\PartyWishlist;
use App\Utils\PaginateCollection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerComplaintController extends Controller
{


    public function index(Request $request)
    {
        $complaints = Complaint::where('customer_id', $request->customerId)->get();

        return new AllComplaintCollection(PaginateCollection::paginate($complaints, $request->pageSize?$request->pageSize:10));

    }



    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $data = $request->validate([
                'customerId' => 'required',
                'title' => 'required',
                'message' => 'required',
            ]);

            $complaint = Complaint::create([
                'customer_id' => $data['customerId'],
                'title' => $data['title'],
                'status' => ComplaintStatus::IN_PROGRESS->value
            ]);

            $complaintMessage = ComplaintMessage::create([
                'complaint_id' => $complaint->id,
                'message' => $data['message'],
                'sender_id' => $data['customerId']
            ]);


            DB::commit();

            return response()->json([
                'message' => 'تم تلقى الشكوي بنجاح'
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

        Complaint::find($id)->delete();

        return response()->json([
            'message' => 'تم حذف الشكوى'
        ]);
    }

}
