<?php

namespace App\Http\Controllers\Api\Customer\Complaint;

use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyReservation;
use App\Models\Party\PartyWishlist;
use App\Models\Party\PreparationTime;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerComplaintController extends Controller
{


    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        // Get current time
        $now = Carbon::now()->toDateTimeString();

        // Determine the type of reservation to return (upcoming or passed)
        $reservationType = $request->reservationType;

        $reservations = DB::table('party_reservations')
            ->join('parties', 'parties.id', '=', 'party_reservations.party_id')
            ->join('party_categories', 'parties.catgory_id', '=', 'party_categories.id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->leftJoin('party_media', function ($join) {
                $join->on('parties.id', '=', 'party_media.party_id')
                    ->where('party_media.type', 0);
            })
            ->select(
                'party_reservations.id as reservationId',
                'party_reservations.price as reservationPrice',
                'party_reservations.end_prep as endPrep',
                'party_reservations.date',
                'parties.id as partyId',
                'parties.name as partyName',
                'cities.name as cityName',
                DB::raw('MIN(party_media.path) as partImage') // Get one record for party_media
            )
            ->where('party_categories.status', 1)
            ->where('party_reservations.customer_id', $user->id)
            ->when($reservationType == 1, function($query) use ($now) {
                // For upcoming reservations, filter by date greater than or equal to now
                return $query->where(DB::raw("CONCAT(party_reservations.date, ' ', party_reservations.end_prep)"), '>=', $now);
            })
            ->when($reservationType == 0, function($query) use ($now) {
                // For passed reservations, filter by date less than now
                return $query->where(DB::raw("CONCAT(party_reservations.date, ' ', party_reservations.end_prep)"), '<', $now);
            })
            ->groupBy('parties.id', 'parties.name', 'cities.name', 'party_reservations.id', 'party_reservations.price', 'party_reservations.end_prep', 'party_reservations.date')
            ->get();

        $formattedReservations = [];

        foreach ($reservations as $party) {
            $averageRate = PartyRate::where('party_id', $party->partyId)->avg('rate');
            $item = [
                'reservationId' => $party->reservationId,
                'partyId' => $party->partyId,
                'partyName' => $party->partyName,
                'date' => $party->date,
                'cityName' => $party->cityName,
                'price' => $party->reservationPrice,
                'partyImage' => $party->partImage ?? "",
                'rate' => round($averageRate, 2),
            ];
            $formattedReservations[] = $item;
        }

        return response()->json([
            'data' => [
                'reservation' => $formattedReservations,
            ]
        ]);
    }



    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $data = $request->validate([
                'partyId' => 'required',
                'userId' => 'required',
                'date' => 'required',
                'preparationId' => 'required',
                'cityId' => 'required'
            ]);

            $preparationTime = PreparationTime::find($data['preparationId']);


            $party = Party::find($data['partyId']);



            $reservation = PartyReservation::create([
                'party_id' => $data['partyId'],
                'customer_id' => $data['userId'],
                'date' => $data['date'],
                'city_id' => $data['cityId'],
                'start_prep' => $preparationTime->start_at,
                'end_prep' => $preparationTime->end_at,
                'status' => ReservationStatus::RESERVED->value,
                'pay_type' => PayType::CARD->value,
                'price' => $party->activePrice(),
                'price_after_discount' => $party->activePrice(),
                'vendor_id' => $party->vendor_id
            ]);


            DB::commit();

            return response()->json([
                'message' => 'تم الحجز بنجاح'
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

        PartyWishlist::find($id)->delete();

        return response()->json([
            'message' => 'تم الحذف من المفضلة'
        ]);
    }

}
