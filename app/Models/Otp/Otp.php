<?php

namespace App\Models\Otp;

use App\Enums\Otp\OtpType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'otp',
        'phone',
        'expire_at',
        'type'
    ];

    protected $casts = [
        'type' => OtpType::class
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->otp = rand(1000, 9999);
            $model->expire_at = Carbon::now()->addMinutes(10);
        });
    }

    public function isValidOtp($inputOtp)
    {
        return $this->otp === $inputOtp && !$this->otpExpired();
    }

    public function otpExpired()
    {
        // OTP is expired if created_at + 10 minutes is in the past
        return Carbon::parse($this->expire_at) < now();
    }

}
