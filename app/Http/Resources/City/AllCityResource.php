<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'cityId' => $this->id,
            'name' => $this->name??"",
            'path' => $this->path?Storage::disk('public')->url($this->path):"",
        ];
    }
}
