<?php

namespace App\Http\Resources\Api\Babysitters;

use App\Http\Resources\Api\User\UserFeatureResource;
use App\Http\Resources\Api\User\UserServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'image'=>$this->avatar,
            'name'=>$this->name,
            'rate_avg'=>4,
            'services'=> UserServiceResource::collection($this->user_services),
            'features'=> UserFeatureResource::collection($this->user_features),
            
        ];
    }
}
