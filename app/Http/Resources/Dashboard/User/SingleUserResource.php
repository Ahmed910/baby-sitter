<?php

namespace App\Http\Resources\Dashboard\User;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleUserResource extends JsonResource
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
            'avatar' => $this->avatar,
            'fullname' => $this->fullname,
          //  route('dashboard'.$this->user_type.'show',$this->id) ,
            'email' => $this->email,
            'phone' => $this->phone,
            'referral_code' => $this->referral_code,
            'referral_code_count' => $this->myReferrals->count(),
            'notify_link' => route('dashboard.notification.store'),
            'show_link' => route('dashboard.ambassador.show',$this->id),
            'created_at' => $this->created_at->format("Y-m-d")
        ];
    }
}
