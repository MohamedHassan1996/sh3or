<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->id,
            'name' => $this->name??"",
            'phone' => $this->phone??"",
            'email' => $this->email??"",
            'avatar' => $this->avatar?Storage::disk('public')->url($this->avatar):"",
            'role' => $this->role??"",
        ];
    }
}
