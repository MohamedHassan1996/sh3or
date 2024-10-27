<?php

namespace App\Services\Payment;

use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;

class MoyasarService
{
    protected $client;
    protected $secretKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->secretKey = config('services.moyasar.secret_key');

    }

    /**
     * Process payment using Moyasar API.
     *
     * @param array $paymentData
     * @return array
     */
    public function processPayment(array $paymentData)
    {
        try {
            $response = $this->client->post('https://api.moyasar.com/v1/payments', [
                'auth' => [$this->secretKey, ''],
                'json' => [
                    'amount' => $paymentData['amount'] * 100, // amount in halalas
                    'currency' => 'SAR',
                    'source' => [
                        'type' => $paymentData['paymentMethod'], // e.g., visa, mada, applepay, etc.
                        'number' => $paymentData['cardNumber'] ?? null,
                        'name' => $paymentData['cardHolderName'] ?? null,
                        'cvc' => $paymentData['cardCvc'] ?? null,
                        'month' => $paymentData['cardExpMonth'] ?? null,
                        'year' => $paymentData['cardExpYear'] ?? null,
                        // Adjust this for other methods like Apple Pay
                    ],
                    'callback_url' => $paymentData['callbackUrl'],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            //dd($result);
            return $result;
        // Check if the status is now "paid"
        if (isset($result['status']) && $result['status'] === 'paid') {
            return [
                'status' => 'success',
                'message' => 'Payment successful!',
                'paymentDetails' => $result
            ];
        } elseif ($result['status'] === 'initiated') {
            return [
                'status' => 'pending',
                'message' => 'Payment is still in progress.'
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => 'Payment failed or cancelled.',
                'payment_details' => $result
            ];
        }
    } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Handle callback after payment.
     *
     * @param array $callbackData
     * @return string
     */
    public function handleCallback(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'status' => 'required|string',
        ]);

        $paymentId = $request->input('id');
        $status = $request->input('status');

        // Update payment status in your database here

        return response()->json(['message' => 'Callback received.']);
    }
}
