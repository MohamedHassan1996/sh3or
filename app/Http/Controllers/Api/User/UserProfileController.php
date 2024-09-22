<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Otp\Otp;
use App\Models\User;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use App\Services\WhatsAppNotification\WhatsAppNotificationService;
use Exception;
use Illuminate\Http\UploadedFile;

class UserProfileController extends Controller
{

    private $whatsAppNotificationService;
    private $uploadService;
    public function __construct(WhatsAppNotificationService $whatsAppNotificationService, UploadService $uploadService)
    {
        $this->whatsAppNotificationService = $whatsAppNotificationService;
        $this->uploadService = $uploadService;
    }


    public function show($id)
    {
        $user = User::find($id);

        return new UserProfileResource($user);
    }

    public function update()
    {
        try{

            $userData = request()->validate([
                'email' => 'required',
                'userId' => 'required',
                'name' => 'required',
                'avatar' => 'nullable'
            ]);

            $user = User::find($userData['userId']);

            $avatarPath = null;

            if(isset($userData['avatar']) && $userData['avatar'] instanceof UploadedFile){
                $avatarPath =  $this->uploadService->uploadFile($userData['avatar'], 'avatars');
            }

            $user = User::find($userData['userId']);
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            //$user->phone = $userData['phone'];

            /*if($userData['password']){
                $user->password = $userData['password'];
            }*/

            //$user->status = UserStatus::from($userData['status'])->value;

            if($avatarPath){
                $user->avatar = $avatarPath;
            }

            $user->save();


            return response()->json([
                'message' => 'تم تحديث الملف الشخصي بنجاح'
            ]);
        }catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
