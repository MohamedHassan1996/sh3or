<?php

namespace App\Models\Party;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyWishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'customer_id',
    ];
}
