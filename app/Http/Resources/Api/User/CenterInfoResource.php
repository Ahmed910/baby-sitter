<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Help\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterInfoResource extends JsonResource
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
            'name'=>$this->name,
            'avatar'=>$this->avatar,
            'avg_rate'=> 4,
            'city'=> new CityResource(optional($this->profile)->city),
            'location'=> optional($this->profile)->location,
            'lat'=>optional($this->profile)->lat,
            'lng'=>optional($this->profile)->lng,
            // 'distance' => $this->distance,
            'services'=> UserServiceResource::collection($this->user_services),
            'is_educational'=>(bool)optional($this->child_centre)->is_educational
        ];
    }
}
