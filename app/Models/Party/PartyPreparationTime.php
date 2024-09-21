<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyPreparationTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'preparation_time_id',
        'status',
    ];

}
