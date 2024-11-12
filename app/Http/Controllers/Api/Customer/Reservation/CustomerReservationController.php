<?php

namespace App\Http\Controllers\Api\Customer\Reservation;

use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
use App\Events\HomeEvent;
use App\Events\MessageNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Party\Party;
use App\Models\Party\PartyRate;
use App\Models\Party\PartyReservation;
use App\Models\Party\PartyWishlist;
use App\Models\Party\PreparationTime;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Services\Payment\MoyasarService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class CustomerReservationController extends Controller
{

    private $moyasarService;

    public function __construct(MoyasarService $moyasarService)
    {

        $this->moyasarService = $moyasarService;
    }


    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        // Get current time
        $now = Carbon::now()->toDateTimeString();

        // Determine the type of reservation to return (upcoming or passed)
        $reservationType = $request->reservationType;

        $reservations = DB::table('party_reservations')
            ->join('parties', 'parties.id', '=', 'party_reservations.party_id')
            ->join('party_categories', 'parties.category_id', '=', 'party_categories.id')
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
                'customerId' => 'required',
                'date' => 'required',
                'preparationTimeId' => 'required',
                'cityId' => 'required'
            ]);

            $preparationTime = PreparationTime::find($data['preparationTimeId']);


            $party = Party::find($data['partyId']);


            $reservation = PartyReservation::create([
                'party_id' => $data['partyId'],
                'customer_id' => $data['customerId'],
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

            $chat = Chat::where('customer_id', $data['customerId'])->where('vendor_id', $party->vendor_id)->first();

            if(!$chat){
                $chat = Chat::create([
                    'customer_id' => $data['customerId'],
                    'vendor_id' => $party->vendor_id
                ]);
            }

            $chatMessage = ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_id' => $party->vendor_id,
                'message' => 'اهلا بيك هذه الرسالة بخصوص متابعة حجز الحفل'
            ]);

            $sender = User::find($party->vendor_id);
            $chatMessage->sende_name = $sender->name;
            $chatMessage->sender_avatar = $sender->avatar??null;

            //broadcast(new HomeEvent($chatMessage));

            /*$payment = $this->moyasarService->processPayment([
                'amount' => $party->activePrice(),
                'currency' => 'SAR',
                'paymentMethod' => $request->paymentMethod,
                'cardNumber' => $request->cardNumber,
                'cardHolderName' => $request->cardHolderName,
                'cardCvc' => $request->cardCvc,
                'cardExpMonth' => $request->cardExpMonth,
                'cardExpYear' => $request->cardExpYear,
                'callbackUrl' => route('payment.callback', ['id' => $reservation->id]),
            ]);*/

            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'payment_guid' => $request->paymentId,
                'payment_number' => $request->paymentId,
                'amount' => $request->paymentAmount,
                'status' =>1,
                'source' => $request->paymentSource,
                'cur' => 'SAR',
                'description' => $request->paymentDescription
            ]);


            DB::commit();

            broadcast(new HomeEvent($chatMessage));
            broadcast(new MessageNotificationEvent($chatMessage));

            return response()->json([
                'message' => 'تم الحجز بنجاح',
                //'paymentId' => $payment['id'],
                //'status' => $payment['status'],
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
