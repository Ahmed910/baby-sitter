<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Help\FeatureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFeatureResource extends JsonResource
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
            'feature'=> new FeatureResource($this->feature)
        ];
    }
}
