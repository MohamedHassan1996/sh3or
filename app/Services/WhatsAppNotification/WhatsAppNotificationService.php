<?php

namespace App\Services\WhatsAppNotification;
use Twilio\Rest\Client;


class WhatsAppNotificationService{

    public function send(string $body, string $recipient)
    {
        $twilio_whatsapp_number = getenv("TWILIO_WHATSAPP_NUMBER");
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $client = new Client($account_sid, $auth_token);

        return $client->messages->create("whatsapp:$recipient", [
            "from" => "whatsapp:$twilio_whatsapp_number",
            "body" => $body
        ]);

    }


}
