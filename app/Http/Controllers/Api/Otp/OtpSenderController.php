<?php

namespace App\Http\Controllers\Api\Otp;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Otp\Otp;
use Illuminate\Http\Request;
use App\Services\WhatsAppNotification\WhatsAppNotificationService;
use Exception;

class OtpSenderController extends Controller
{

    private $whatsAppNotificationService;
    public function __construct(WhatsAppNotificationService $whatsAppNotificationService)
    {
        $this->whatsAppNotificationService = $whatsAppNotificationService;
    }

    public function store()
    {
        try{

            $data = request()->validate([
                'phone' => 'required',
                'type' => 'required'
            ]);

            $otp = Otp::create([
                'phone' => $data['phone'],
                'type' => $data['type']
            ]);

            $this->whatsAppNotificationService->send($otp->otp, $data['phone']);

            return response()->json([
            'message' => 'تم ارسال الرمز تحقق من رسائل الواتس!'
            ]);
        }catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
