<?php

namespace App\Models\Party;

use App\Enums\Party\PartyCancelStatus;
use App\Enums\Party\PartyStatus;
use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
        'status',
        'allow_cancel',
        'category_id',
        'city_id',
        'vendor_id',
    ];

    protected $casts = [
        'status' => PartyStatus::class,
        'allow_cancel' => PartyCancelStatus::class
    ];

    public function category()
    {
        return $this->belongsTo(PartyCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInActive($query)
    {
        return $query->where('status', 0);
    }

    public function activePrice()
    {
        return DB::table('party_price_lists')
            ->join('price_lists', 'party_price_lists.pricelist_id', '=', 'price_lists.id')
            ->where('party_price_lists.status', PriceListStatus::ACTIVE->value)
            ->where('party_price_lists.party_id', $this->id)
            ->where('party_price_lists.type', PriceListType::MAIN->value)
            ->select('price_lists.price')
            ->value('price_lists.price');
    }


}
