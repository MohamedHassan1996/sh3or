<?php

namespace App\Models\Party;

use App\Enums\Party\CategoryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'path',
    ];

    protected $cast = [
        'status' => CategoryStatus::class
    ];
}
