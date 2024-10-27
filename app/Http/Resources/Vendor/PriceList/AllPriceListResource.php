<?php

namespace App\Http\Resources\Vendor\PriceList;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPriceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "priceListId" => $this->id,
            "name" => $this->name,
            'price' => $this->price,
            'startAt' => $this->start_at? Carbon::parse($this->start_at)->format('d-m-Y'):null,
            'endAt' => $this->end_at? Carbon::parse($this->end_at)->format('d-m-Y'):null,
        ];


    }
}
