<?php

namespace App\Http\Controllers\Api\Vendor\PaymentSummrize;

use App\Http\Controllers\Controller;
use App\Models\Party\PartyReservation;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{


    public function index(Request $request)
    {
        $vendorId = $request->vendorId;

        $partyReservation = PartyReservation::with('payment')->where('vendor_id', $vendorId)->get();

        return response()->json([
            'data' => [
                'totalPayments' => $partyReservation,
            ]
        ]);
    }


}
