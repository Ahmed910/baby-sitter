<?php

namespace App\Http\Resources\Api\Client;

use App\Http\Resources\Api\Help\CityResource;
use App\Http\Resources\Api\User\UserServiceResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::findOrFail($this->user_id);
        return [
            
            'id'=>$this->id,
            'name'=>$user->name,
            'avatar'=>$user->avatar,
            'sitter_id'=>$this->user_id,
            'services'=> UserServiceResource::collection($user->user_services),
            'is_fav'       => isfav($this->user_id),
            'city' => $this->when($user->user_type =='childcenter',optional($user->profile)->city_id ? new CityResource($user->profile->city) : null),
            'lat'=>$this->when($user->user_type =='childcenter',optional($user->profile)->lat),
            'lng'=>$this->when($user->user_type =='childcenter',optional($user->profile)->lng),
            'location' =>$this->when($user->user_type =='childcenter',optional($user->profile)->location)
        ];
    }
}
