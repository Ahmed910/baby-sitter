<?php

namespace App\Http\Resources\Api\Client\Store;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'lat' => (float)$this->lat,
            'lng' => (float)$this->lng,
            'location' => $this->location,
            'image' => $this->image,
            'has_offers' => $this->products()->whereNotNull('offer_price')->exists(),
            'category' => new CategoryResource($this->category),
        ];
    }
}
