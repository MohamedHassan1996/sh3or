<?php

namespace App\Http\Resources\Customer\Complaint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'complaintId' => $this->id,
            'title' => $this->title,
            'complaintNumber' => $this->complaint_number,
            'status' => $this->status,
        ];
    }
}
