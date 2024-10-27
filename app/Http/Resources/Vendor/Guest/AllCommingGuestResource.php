<?php

namespace App\Http\Resources\Vendor\Guest;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCommingGuestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'guestName' => $this->customerName,
            "resrvationDate" => Carbon::parse($this->reservationDate)->format('d-m-Y'),
            "resrvationNumber" => $this->reservation_number
        ];


    }
}
