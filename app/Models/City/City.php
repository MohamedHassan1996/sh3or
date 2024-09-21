<?php

namespace App\Models\City;

use App\Enums\City\CityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'status'];

    protected $casts = [
        'status' => CityStatus::class
    ];
}
