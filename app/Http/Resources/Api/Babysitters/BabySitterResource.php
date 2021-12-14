<?php

namespace App\Http\Resources\Api\Babysitters;

use App\Http\Resources\Api\Gallery\GalleryResource;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Http\Resources\Api\User\RateFromUserResource;
use App\Http\Resources\Api\User\UserFeatureResource;
use App\Http\Resources\Api\User\UserServiceResource;
use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class BabySitterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews = Rate::where('to',$this->id)->get();
        
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'avatar'=>$this->avatar,
            'bio'=>optional($this->profile)->bio,
            'services'=> UserServiceResource::collection($this->user_services),
            'rate_avg' => $this->rate_avg,
            'features' => UserFeatureResource::collection($this->user_features),
            'galleries' => GalleryResource::collection($this->galleries),
            'reviews' => RateFromUserResource::collection($reviews),
            'is_fav'       => isfav($this->id),

        ];
    }
}
