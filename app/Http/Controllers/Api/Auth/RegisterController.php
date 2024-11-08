<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Otp\OtpType;
use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\Otp\Otp;
use App\Models\User;
use App\Services\WhatsAppNotification\WhatsAppNotificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    private $whatsAppNotificationService;
    public function __construct(WhatsAppNotificationService $whatsAppNotificationService)
    {
        $this->whatsAppNotificationService = $whatsAppNotificationService;
    }
    public function store(AuthRegisterRequest $authRegisterRequest)
    {
        try{
            DB::beginTransaction();
            $data = $authRegisterRequest->validated();

            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'status' => UserStatus::INACTIVE->value,
                'role' => $data['role']
            ]);

            $otp = Otp::create([
                'phone' => $data['phone'],
                'type' => OtpType::REGISTER->value,
            ]);


            /*$this->whatsAppNotificationService->send(
                $otp->otp,
                $otp->phone
            );*/

            DB::commit();

            return response()->json([
                'message' => 'يرجى تفعيل الحساب',
                'data' => [
                    'phone' => $user->phone,
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
