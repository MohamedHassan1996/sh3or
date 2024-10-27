<?php

namespace App\Http\Resources\Customer\Slider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'sliderId' => $this->id,
            'title' => $this->title??"",
            'path' => $this->path?Storage::disk('public')->url($this->path):"",
        ];
    }
}
