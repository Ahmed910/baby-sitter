<?php

namespace App\Http\Resources\Api\Babysitters;

use App\Http\Resources\Api\Gallery\GalleryResource;
use App\Http\Resources\Api\User\UserFeatureResource;
use App\Http\Resources\Api\User\UserServiceResource;
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
        $reviews = [
            [
                'id'=>'2aw-23e-ews-2e33',
                'avatar'=>asset('dashboardAssets/images/backgrounds/avatar.jpg'),
                'name'=> 'ahmed',
                'review'=>'very good',
                'sitter_rate'=> 4
            ],
            [
                'id'=>'2aw-23e-ews-2ew3',
                'avatar'=>asset('dashboardAssets/images/backgrounds/avatar.jpg'),
                'name'=> 'mohamed',
                'review'=>'very good',
                'sitter_rate'=> 3
            ]
            ];
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'avatar'=>$this->avatar,
            'bio'=>optional($this->profile)->bio,
            'services'=> UserServiceResource::collection($this->user_services),
            'rate_avg' => $this->rate_avg,
            'features' => UserFeatureResource::collection($this->user_features),
            'galleries' => GalleryResource::collection($this->galleries),
            'reviews' => $reviews,
            'is_fav'       => isfav($this->id),

        ];
    }
}
