<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
          'offer_id' => $this->id,
          'driver_id' => $this->driver_id,
          'created_at' => $this->created_at->format('Y-m-d'),
          'offer_price' => (string)$this->offer_price,
          'offer_status' => $this->price_offer_status,
          'cost_reason' => $this->cost_reason,
         ];
    }
}
