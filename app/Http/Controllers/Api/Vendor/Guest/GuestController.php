<?php

namespace App\Http\Controllers\Api\Vendor\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\Guest\AllCommingGuestCollection;
use App\Utils\PaginateCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{


    public function index(Request $request)
    {
        $commingGuests = DB::table('party_reservations')
            ->join('users', 'party_reservations.customer_id', '=', 'users.id')
            ->join('parties', 'parties.id', '=', 'party_reservations.party_id')
            ->where('party_reservations.date', '>=', Carbon::today())
            ->where('party_reservations.vendor_id', $request->vendorId)
            ->select('party_reservations.date as reservationDate', 'party_reservations.reservation_number', 'users.name as customerName', 'parties.name as partyName')
            ->get();

        return new AllCommingGuestCollection(PaginateCollection::paginate(collect($commingGuests), $request->pageSize?$request->pageSize:10));


    }

}
