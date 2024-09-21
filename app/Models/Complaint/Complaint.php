<?php

namespace App\Models\Complaint;

use App\Enums\Complaint\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number',
        'customer_id',
        'user_id',
        'status'
    ];

    protected $casts = [
        'status' => ComplaintStatus::class
    ];

    private function generateComplaintNumber(): string
    {
        $timestampStr = substr((string)time(), 0, 6);

        return "COM-" . generateUniqNumber(4) . $timestampStr;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->complaint_number = $model->generateComplaintNumber();
        });

    }

}
