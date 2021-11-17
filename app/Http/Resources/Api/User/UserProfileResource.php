<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Help\{CityResource,CountryResource};
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
            'name' => $this->name,
            'phone' => (string)$this->phone,
            'email' => (string)$this->email,
            'image' => (string)$this->avatar,
            'qr_code'=>$this->qr_code,
            'test_version' => (string)setting('test_version'),
            'unread_notifications' => $this->unreadnotifications->count(),
            'date_of_birth' => $this->when($this->user_type == 'driver',optional($this->date_of_birth)->format("Y-m-d")),
            'date_of_birth_hijri' => $this->when($this->user_type == 'driver',optional($this->date_of_birth_hijri)->format("Y-m-d")),
            'identity_number' => $this->when($this->user_type == 'driver',$this->identity_number),
            'identity_number_image' => $this->when($this->user_type == 'driver',$this->identity_number_image),
            'services'=> $this->when($this->user_type !='client',UserServiceResource::collection($this->user_services)),
            'is_educational'=>(bool)$this->when($this->user_type =='childcenter',optional($this->child_centre)->is_educational),
            'user_type' => (string)$this->user_type,

            'unread_notifications' => $this->unreadnotifications->count(),

            'token' => $this->when($this->token,$this->token),
            'country' => optional($this->profile)->country_id ? new CountryResource($this->profile->country) : null,
            'city' => optional($this->profile)->city_id ? new CityResource($this->profile->city) : null,
            'lat'=>optional($this->profile)->lat,
            'lng'=>optional($this->profile)->lng,

        ];
    }
}
