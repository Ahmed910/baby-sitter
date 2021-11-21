<?php

namespace App\Http\Resources\Api\Offers;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleOfferResource extends JsonResource
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
            'title'=>$this->title,
            'start_date'=> $this->start_date->format('d M Y'),
            'end_date'=> $this->end_date->format('d M Y'),
            'max_number'=> $this->max_num,
            'promo_code' => $this->promo_code,
            'num_of_used'=> 50,
            'discount'=> $this->discount,
            'photo'=>$this->photo
        ];
    }
}
