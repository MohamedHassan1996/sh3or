<?php

namespace App\Http\Controllers\Api\Payment;

use App\Events\HomeEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Party\PartyReservation;
use App\Models\Payment\Payment;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerPaymentController extends Controller
{
    /*public function processPayment(Request $request)
    {
        $client = new Client();

        // Define the payment method dynamically based on input
        $paymentMethod = $request->input('payment_method'); // 'visa', 'mada', 'applepay', etc.

        $response = $client->post('https://api.moyasar.com/v1/payments', [
            'auth' => [config('services.moyasar.secret_key'), ''],
            'json' => [
                'amount' => $request->input('amount') * 100, // Amount in halalas (e.g., 100 SAR = 10000 halalas)
                'currency' => 'SAR', // Saudi Riyals
                'source' => [
                    'type' => $paymentMethod,
                    // For card payments (Visa/Mada)
                    'number' => $request->input('card_number'),
                    'name' => $request->input('card_holder_name'),
                    'cvc' => $request->input('card_cvc'),
                    'month' => $request->input('card_exp_month'),
                    'year' => $request->input('card_exp_year'),
                    // For Apple Pay and other methods, adjust the source attributes accordingly
                ],
                'callback_url' => route('payment.callback'), // Callback URL to handle the result
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        if ($result['status'] === 'paid') {
            // Payment was successful
            return response()->json(['status' => 'Payment successful', 'payment_id' => $result['id']]);
        } else {
            // Payment failed
            return response()->json(['status' => 'Payment failed', 'message' => $result['message']]);
        }
    }*/

    public function paymentCallback(Request $request)
{
    // Validate the incoming request for necessary fields
    $request->validate([
        'id' => 'required|string',
        'status' => 'required|string',
        'reservationId' => 'required|integer', // Assuming this is also needed
    ]);

    $paymentId = $request->input('id');
    $partyReservationId = $request->input('reservationId');

    // Generate the basic authorization token using the secret key
    $token = base64_encode(config('services.moyasar.secret_key') . ':');

    // Make the API request to Moyasar to fetch the payment details
    $response = Http::baseUrl('https://api.moyasar.com/v1/')
        ->withHeaders([
            'Authorization' => 'Basic ' . $token,
        ])
        ->get('payments/' . $paymentId)
        ->json();

    // Check if the response has a status and handle accordingly
    if (isset($response['status'])) {
        if ($response['status'] === 'paid') {
            // Handle successful payment
            $partyReservation = PartyReservation::find($partyReservationId);

            // Update reservation with payment ID
            $partyReservation->payment_id = $paymentId;
            $partyReservation->save();

            $chat = Chat::where('customer_id', $partyReservation->customer_id)->where('vendor_id', $partyReservation->vendor_id)->first();

            if(!$chat){
                $chat = Chat::create([
                    'customer_id' => $partyReservation->customer_id,
                    'vendor_id' => $partyReservation->vendor_id
                ]);
            }

            $chatMessage = ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_id' => $partyReservation->vendor_id,
                'message' => 'اهلا بيك هذه الرسالة بخصوص متابعة حجز الحفل'
            ]);

            $sender = User::find($partyReservation->vendor_id);
            $chatMessage->sende_name = $sender->name;
            $chatMessage->sender_avatar = $sender->avatar??null;

            $paymentGuid = explode('-',  $response['id']);
            $paymentNumber = end($paymentGuid);
            Payment::create([
                'reservation_id' => $partyReservation->id,
                'payment_guid' => $response['id'],
                'payment_number' => $paymentNumber,
                'amount' => $response['amount'],
                'status' => $response['status'] == 'paid' ? 1 : 0,
                'source' => $response['source']['type'],
                'cur' => $response['currency'],
                'description' => $response['description']
            ]);

            broadcast(new HomeEvent($chatMessage));
            return response()->json(['message' => 'تم الحجز بنجاح']);



        } elseif ($response['status'] === 'failed') {
            // Handle failed payment
            $failureReason = $response['message'] ?? 'فشلت عملية الدفع';

            // Optionally, delete the reservation on failure
            $partyReservation = PartyReservation::find($partyReservationId);
            if ($partyReservation) {
                $partyReservation->delete();
            }

            return response()->json(['message' => $failureReason], 401);
        } else {
            // Handle other cases like declined transactions
            $errorReason = $response['message'] ?? 'Payment failed.';

            return response()->json(['message' => $errorReason], 401);
        }
    } else {
        return response()->json(['message' => 'Invalid response from payment gateway.'], 500);
    }
}

private function capturePayment($paymentId, $token)
{
    // Make the API request to Moyasar to capture the payment
    $captureResponse = Http::baseUrl('https://api.moyasar.com/v1/')
        ->withHeaders([
            'Authorization' => 'Basic ' . $token,
        ])
        ->post('payments/' . $paymentId . '/capture') // Assuming this is the correct endpoint for capturing payments
        ->json();

    return $captureResponse;
}



}
