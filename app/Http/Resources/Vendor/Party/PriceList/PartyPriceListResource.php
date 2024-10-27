<?php

namespace App\Http\Resources\Vendor\Party\PriceList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartyPriceListResource extends JsonResource
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
            "partId" => $this->party_id,
            'status' => $this->status,
            'type' => $this->type,
        ];


    }
}
