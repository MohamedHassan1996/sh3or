<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLoginController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);


        $token = Auth::guard('api')->attempt(['phone' => $data['phone'], 'password' => $data['password']]);

        if (!$token) {
            return response()->json([
                'message' => 'رقم او كلمة مرور خاطئة!'
            ], 401);
        }

        $user = Auth::guard('api')->user();


        return response()->json([
            'data' => [
                'token' => $token,
                'user' => new UserProfileResource($user)
            ]
        ]);

    }
}
