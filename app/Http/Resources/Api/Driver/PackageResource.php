<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'package_type' => $this->package_type,
            'package_price' => $this->package_price,
            'package_percent' => $this->package_percent,
            'initial_duration' => $this->initial_duration,
            'number_of_orders_for_free' => $this->number_of_orders_for_free,
            'is_subscribed' => $this->subscribers()->where(['driver_id' => auth('api')->id(),'is_paid' => 1])->whereDate('end_at',">",date("Y-m-d"))->exists(),
            'tax' => (float) setting('tax'),
        ];
    }

}
