<?php

namespace App\Models\Party;

use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => PriceListStatus::class,
    ];
}
