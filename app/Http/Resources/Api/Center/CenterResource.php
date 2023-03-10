<?php

namespace App\Http\Resources\Api\Center;

use App\Http\Resources\Api\Gallery\GalleryResource;
use App\Http\Resources\Api\Help\CityResource;
use App\Http\Resources\Api\Schedules\AppointmentResource;
use App\Http\Resources\Api\Schedules\ScheduleResource;
use App\Http\Resources\Api\User\RateFromUserResource;
use App\Http\Resources\Api\User\UserFeatureResource;
use App\Http\Resources\Api\User\UserServiceResource;
use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews = Rate::where('to_center',$this->id)->get();

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'avatar'=>$this->avatar,
            'avg_rate'=> $this->rate_avg,
            'city'=> new CityResource(optional($this->profile)->city),
            'location'=> optional($this->profile)->location,
            'lat'=>optional($this->profile)->lat,
            'lng'=>optional($this->profile)->lng,
            // 'distance' => $this->distance,
            'services'=> UserServiceResource::collection($this->user_services),
            'is_educational'=>(bool) optional($this->child_center)->is_educational,
            'galleries' => GalleryResource::collection($this->galleries),
            'features' => UserFeatureResource::collection($this->user_features),
            'schedules' => ScheduleResource::collection($this->schedules),
            'appointment' => new AppointmentResource($this->appointment),
            'sitters'=> BabySitterCenterResource::collection($this->sittersForCenter),
            'reviews' => RateFromUserResource::collection($reviews)
        ];
    }
}
