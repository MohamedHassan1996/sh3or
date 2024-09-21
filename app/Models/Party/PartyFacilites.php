<?php

namespace App\Models\Party;

use App\Enums\Facility\FStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyFacilites extends Model
{
    use HasFactory;

    protected $table = 'party_facilites';

    protected $fillable = [
        'party_id',
        'facility_id',
        'status',
    ];

    protected $casts = [
        'status' => FStatus::class,
    ];
}
