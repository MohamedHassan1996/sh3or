<?php

namespace App\Http\Controllers\Api\Vendor\Reservation;

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

class VendorReservationController extends Controller
{


    public function index(Request $request)
    {
        //$user = Auth::guard('api')->user();

        // Get current time
        $now = Carbon::now()->toDateTimeString();

        // Determine the type of reservation to return (upcoming or passed)
        $reservationType = $request->reservationType;
        $toShow = $request->toShow;

        // Initialize date filters
        $startDate = Carbon::now()->toDateString();
        $endDate = null;

        // Modify query based on the toShow parameter
        if ($toShow === 'weekly') {
            // Get the end of the next week from today
            $endDate = Carbon::now()->addWeek()->toDateString();
        } elseif ($toShow === 'monthly') {
            // Get the end of the next month from today
            $endDate = Carbon::now()->addMonth()->toDateString();
        }

        $reservations = DB::table('party_reservations')
            ->join('parties', 'parties.id', '=', 'party_reservations.party_id')
            ->join('party_categories', 'parties.category_id', '=', 'party_categories.id')
            ->join('cities', 'parties.city_id', '=', 'cities.id')
            ->select(
                'party_reservations.id as reservationId',
                'party_reservations.price as reservationPrice',
                'party_reservations.end_prep as endPrep',
                'party_reservations.start_prep as startPrep',
                'party_reservations.date',
                'parties.id as partyId',
                'parties.name as partyName',
                'cities.name as cityName'
            )
            ->where('party_reservations.vendor_id', $request->vendorId)
            // Filter for upcoming or passed reservations
            ->when($reservationType == 1, function ($query) use ($now) {
                return $query->where(DB::raw("CONCAT(party_reservations.date, ' ', party_reservations.end_prep)"), '>=', $now);
            })
            ->when($reservationType == 0, function ($query) use ($now) {
                return $query->where(DB::raw("CONCAT(party_reservations.date, ' ', party_reservations.end_prep)"), '<', $now);
            })
            // Filter for weekly or monthly reservations from now forward
            ->when($toShow === 'weekly' || $toShow === 'monthly', function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('party_reservations.date', [$startDate, $endDate]);
            })
            // Get the latest reservation when toShow is 'latest'
            ->when($toShow === 'latest', function ($query) {
                return $query->orderBy('party_reservations.date', 'desc')->limit(1); // Fetch the latest reservation
            })
            ->orderBy('party_reservations.date', $request->orderType)
            ->get();


        $formattedReservations = [];

        foreach ($reservations as $party) {
            //$averageRate = PartyRate::where('party_id', $party->partyId)->avg('rate');
            $item = [
                'reservationId' => $party->reservationId,
                'partyId' => $party->partyId,
                'partyName' => $party->partyName,
                'date' => $party->date,
                'startPreparation' => Carbon::parse($party->startPrep)->format('H:i'),
                'endPreparation' => Carbon::parse($party->endPrep)->format('H:i'),
                'cityName' => $party->cityName,
                'price' => $party->reservationPrice,
                //'partyImage' => $party->partImage ?? "",
                //'rate' => round($averageRate, 2),
            ];
            $formattedReservations[] = $item;
        }

        return response()->json([
            'data' => [
                'reservations' => $formattedReservations,
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

        PartyReservation::find($id)->delete();

        return response()->json([
            'message' => 'تم الغاء الحجز بنجاح'
        ]);
    }

}
