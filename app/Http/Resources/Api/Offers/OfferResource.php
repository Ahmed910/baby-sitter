<?php

namespace App\Http\Resources\Api\Offers;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = auth('api')->user();
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'offer_photo'=>$this->photo,
            'discount'=> $this->discount,
            'promo_code' => $this->promo_code,
            'status'=>$this->when((isset($user) && ($user->user_type == 'babysitter' || $user->user_type == 'childcenter')), $this->status)
        ];
    }
}
