<?php

namespace App\Http\Controllers\Api\Customer\Complaint;

use App\Enums\Complaint\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\Complaint\ComplaintMessage\AllComplaintMessageResource;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintMessage;
use App\Utils\PaginateCollection;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerComplaintMessageController extends Controller
{


    public function index(Request $request)
    {
        $complaintMessages = ComplaintMessage::where('complaint_id', $request->complaintId)->get();

        return [
            'complaintMessages' => AllComplaintMessageResource::collection($complaintMessages)
        ];

    }



    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $data = $request->validate([
                'senderId' => 'required',
                'message' => 'required',
                'complaintId' => 'required',
            ]);

            $complaintMessage = ComplaintMessage::create([
                'complaint_id' => $data['complaintId'],
                'message' => $data['message'],
                'sender_id' => $data['senderId']
            ]);


            DB::commit();

            return response()->json([
                'message' => 'تم ارسال الرد بنجاح'
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

        ComplaintMessage::find($id)->delete();

        return response()->json([
            'message' => 'تم حذف الرد'
        ]);
    }

}
