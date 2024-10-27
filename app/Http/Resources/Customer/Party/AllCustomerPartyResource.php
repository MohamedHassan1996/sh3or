<?php

namespace App\Http\Resources\Customer\Party;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCustomerPartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "partyId" => $this['partyId'],
            "partyName" => $this['partyName'],
            "cityName" => $this['cityName'],
            "price" => $this['price'],
            "partyImage" => $this['partyImage']?Storage::disk('public')->url($this['partyImage']):"",
            "rate" => $this['rate'],
            "inWishlist" => $this['inWishlist']
        ];


    }
}
