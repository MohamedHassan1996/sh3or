<?php

namespace App\Models\Facility;

use App\Enums\Facility\FStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'status',
    ];

    protected $casts = [
        'status' => FStatus::class,
    ];
}
