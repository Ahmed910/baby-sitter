<?php

namespace App\Http\Resources\Api\Offers;

use App\Models\MainOrder;
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
        $num_of_used = MainOrder::where('offer_id',$this->id)->count();
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'start_date'=> $this->start_date->toFormattedDateString(),
            'end_date'=> $this->end_date->toFormattedDateString(),
            'max_number'=> $this->max_num,
            'promo_code' => $this->promo_code,
            'num_of_used'=> $num_of_used,
            'status'=> $this->status,
            'discount'=> $this->discount,
            'photo'=>$this->photo,
            'offer_fees'=>setting('offer_fees'),
            'is_reactive'=>$this->getOfferIsReactive()
        ];
    }

    private function getOfferIsReactive()
    {
        $orders_with_that_offer = MainOrder::where('offer_id',$this->id)->count();
       if(now() > $this->end_date || $orders_with_that_offer == $this->max_num || $this->status =='inactive'){
            $is_reactive = true;
        }else{
            $is_reactive = false;
        }
        return $is_reactive;
    }
}
