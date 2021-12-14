<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class RateFromUserResource extends JsonResource
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
            'id' => $this->id,
            'user_data' => [
                'id' => optional($this->fromUser)->id,
                'name' => optional($this->fromUser)->name,
                'avatar' => optional($this->fromUser)->avatar,
            ],
            'rate'=>$this->rate,
            'review'=>$this->review
        ];
    }
}
