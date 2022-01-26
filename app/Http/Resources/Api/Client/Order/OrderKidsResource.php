<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Client\KidResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderKidsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $kid = $this->kid;
        return [
            'id'=>$kid->id,
            // 'kid'=>new KidResource($this->kid)
            'kidname'=>$kid->kidname,
            'age'=>$kid->age,
            'health_state'=>$kid->health_state,
            'image'=>$kid->image
        ];
    }
}
