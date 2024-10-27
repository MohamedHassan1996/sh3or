<?php

namespace App\Http\Resources\Customer\Complaint\ComplaintMessage;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllComplaintMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'complaintMessageId' => $this->id,
            'message' => $this->message,
            'createdAt' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'senderId' => $this->sender_id,
            'senderName' => $this->sender->name,
            'senderAvatar' => Storage::url($this->sender->avatar),
        ];
    }
}
