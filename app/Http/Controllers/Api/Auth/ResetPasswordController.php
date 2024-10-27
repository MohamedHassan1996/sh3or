<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Otp\OtpType;
use App\Http\Controllers\Controller;
use App\Models\Otp\Otp;
use App\Models\User;
use App\Services\WhatsAppNotification\WhatsAppNotificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    private $whatsAppNotificationService;
    public function __construct(WhatsAppNotificationService $whatsAppNotificationService)
    {
        $this->whatsAppNotificationService = $whatsAppNotificationService;
    }
    public function store()
    {
        try{
            DB::beginTransaction();
            $data = request()->validate([
                'phone' => 'required',
                'otp' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('phone', $data['phone'])->first();

            if (!$user) {

                return response()->json([
                    'message' => 'هذا الحساب غير موجود'
                ]);
            }

            $otp = Otp::where('otp', $data['otp'])->where('phone', $data['phone'])->first();


            if (!$otp) {
                return response()->json([
                    'message' => 'هذا الرمز غير صالح!',
                ]);
            }

            if (!$otp->isValidOtp($data['otp']) || $otp->type->value != OtpType::RESET->value) {
                return response()->json([
                    'message' => 'هذا الرمز غير صالح!',
                ]);
            }

            $user->password = $data['password'];

            $user->save();

            $otp->delete();

            DB::commit();

            return response()->json([
                'message' => 'تم تغيير الرقم السرى بنجاح',
            ]);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
