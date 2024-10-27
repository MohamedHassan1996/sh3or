<?php

namespace App\Http\Resources\PartyPreparationTime;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPartyPreparationTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'preparationTimeId' => $this->id,
            'startAt' => Carbon::parse( $this->start_at)->format('H:i'),
            'endAt' => Carbon::parse( $this->end_at)->format('H:i'),
        ];
    }
}
