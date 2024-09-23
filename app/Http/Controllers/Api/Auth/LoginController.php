<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\User\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Resources\User\UserProfileResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(AuthLoginRequest $authLoginRequest)
    {
        $data = $authLoginRequest->validated();

        $token = Auth::guard('api')->attempt(['phone' => $data['phone'], 'password' => $data['password']]);

        if (!$token) {
            return response()->json([
                'message' => 'رقم او كلمة مرور خاطئة!'
            ], 401);
        }

        $user = Auth::guard('api')->user();


        if ($user->status->value == UserStatus::INACTIVE->value) {
            return response()->json([
                'message' => 'الحساب غير مفعل!'
            ], 401);
        }


        return response()->json([
            'data' => [
                'token' => $token,
                'user' => new UserProfileResource($user)
            ]
        ]);

    }
}
