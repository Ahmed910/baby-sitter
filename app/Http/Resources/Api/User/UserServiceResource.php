<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Help\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            // 'service' => new ServiceResource($this->service),
            'service_type'=>optional($this->service)->service_type,
            'price'=>(float)$this->price
        ];
    }
}
