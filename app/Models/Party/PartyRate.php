<?php

namespace App\Models\Party;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyRate extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $fillable = [
        'party_id',
        'customer_id',
        'rate',
    ];

    protected $casts = [
        'rate' => 'float',
    ];
}
