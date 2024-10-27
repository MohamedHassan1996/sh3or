<?php

namespace App\Http\Resources\Vendor\Party\PriceList;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPartyPriceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "partyPriceListId" => $this->id,
            "price" => $this->pricelist->price,
            'status' => $this->status,
            'type' => $this->type,
            "name" => $this->pricelist->name,
            'startAt' => $this->pricelist->start_at? Carbon::parse($this->pricelist->start_at)->format('d-m-Y'):null,
            'endAt' => $this->pricelist->end_at? Carbon::parse($this->pricelist->end_at)->format('d-m-Y'):null,
        ];


    }
}
