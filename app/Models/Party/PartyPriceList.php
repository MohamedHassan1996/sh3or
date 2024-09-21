<?php

namespace App\Models\Party;

use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyPriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'pricelist_id',
        'status',
        'type'
    ];

    protected $casts = [
        'status' => PriceListStatus::class,
        'type' => PriceListType::class
    ];
}
