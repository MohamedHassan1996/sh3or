<?php

namespace App\Http\Controllers\Api\Vendor\PaymentSummrize;

use App\Http\Controllers\Controller;
use App\Models\Party\PartyReservation;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;

class PaymentSummrizeController extends Controller
{


    public function index(Request $request)
    {
        $vendorId = $request->vendorId;

        $partyReservation = PartyReservation::where('vendor_id', $vendorId)->pluck('id');

        $totalPayments = Payment::whereIn('reservation_id', $partyReservation)->sum('amount');


        return response()->json([
            'data' => [
                'totalPayments' => $totalPayments,
            ]
        ]);
    }


}
