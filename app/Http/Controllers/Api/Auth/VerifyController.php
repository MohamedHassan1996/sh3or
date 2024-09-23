<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Otp\OtpType;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Otp\Otp;
use App\Models\User;
use App\Services\WhatsAppNotification\WhatsAppNotificationService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class VerifyController extends Controller
{
    private $whatsAppNotificationService;
    public function __construct(WhatsAppNotificationService $whatsAppNotificationService)
    {
        $this->whatsAppNotificationService = $whatsAppNotificationService;
    }
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $data = request()->validate([
                'phone' => 'required',
                'otp' => 'required',
            ]);

            $user = User::where('phone', $data['phone'])->first();

            if (!$user) {

                return [
                    'success' => false,
                    'message' => 'هذا الحساب غير موجود',
                ];
            }

            $otp = Otp::where('otp', $data['otp'])->where('phone', $data['phone'])->first();

            if (!$otp->isValidOtp($data['otp']) || $otp->type->value != OtpType::REGISTER->value) {
                return response()->json([
                    'message' => 'هذا الرمز غير صالح!',
                ]);
            }

            $user->verifyAccount();

            $token = JWTAuth::fromUser($user);

            $otp->delete();

            DB::commit();

            return response()->json(data: [
                'data' => [
                    'token' => $token,
                    'user' => new UserProfileResource($user)
                ]
            ]);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
