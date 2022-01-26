<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Center\BabySitterCenterResource;
use App\Http\Resources\Api\Gallery\GalleryResource;
use App\Http\Resources\Api\Help\{CityResource,CountryResource};
use App\Http\Resources\Api\Schedules\AppointmentResource;
use App\Http\Resources\Api\Schedules\ScheduleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_type' => (string)$this->user_type,
            'name' => $this->name,
            'phone' => (string)$this->phone,
            'email' => (string)$this->email,
            'image' => (string)$this->avatar,
            'rate_avg'=> 4,
            'qr_code'=>$this->qr_code,
            // 'test_version' => (string)setting('test_version'),
            'unread_notifications' => $this->unreadnotifications->count(),
            'identity_number' => $this->when($this->user_type != 'childcenter',$this->identity_number),
            'services'=> $this->when($this->user_type !='client',UserServiceResource::collection($this->user_services)),
            'is_educational'=>$this->when($this->user_type =='childcenter',(bool)optional($this->child_centre)->is_educational),
            'price_educational'=>$this->when(($this->user_type =='childcenter' && optional($this->child_centre)->is_educational)==true,(float)optional($this->child_centre)->price),
            'business_register'=>$this->when($this->user_type =='childcenter',optional($this->child_centre)->business_register),
            'business_register_image'=>$this->when($this->user_type =='childcenter',optional($this->child_centre)->business_license_image),
            'bio'=>$this->when($this->user_type =='babysitter',optional($this->profile)->bio),
            'galleries' => $this->when($this->user_type !='client',GalleryResource::collection($this->galleries)),
            'features' => $this->when($this->user_type !='client',UserFeatureResource::collection($this->user_features)),

            'schedules' => $this->when($this->user_type !='client',ScheduleResource::collection($this->schedules)),
            'appointment' => $this->when($this->user_type !='client',new AppointmentResource($this->appointment)),
            'sitters'=> $this->when($this->user_type =='childcenter',BabySitterCenterResource::collection($this->sittersForCenter)),
            'unread_notifications' => $this->unreadnotifications->count(),

            'token' => $this->when($this->token,$this->token),
            'country' => optional($this->profile)->country_id ? new CountryResource($this->profile->country) : null,
            'city' => optional($this->profile)->city_id ? new CityResource($this->profile->city) : null,
            'lat'=>optional($this->profile)->lat,
            'lng'=>optional($this->profile)->lng,
            'location'=>$this->when($this->user_type =='childcenter',optional($this->profile)->location)

        ];
    }
}
