<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class RateForBabySitterResource extends JsonResource
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
            'user_data'=>[
                'id'=>$this->toBabySitter->id,
                'name'=>$this->toBabySitter->name
            ],
            'rate'=>$this->rate,
            'review'=>$this->review
        ];
    }
}
