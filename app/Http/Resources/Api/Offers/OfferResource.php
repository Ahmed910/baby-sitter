<?php

namespace App\Http\Resources\Api\Offers;

use App\Models\MainOrder;
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
            // 'admin_approved_status'=>$this->when((isset($user) && ($user->user_type == 'babysitter' || $user->user_type == 'childcenter')), $this->status),
            'status'=>$this->when((isset($user) && ($user->user_type == 'babysitter' || $user->user_type == 'childcenter')), $this->getOfferStatus())
        ];
    }

    private function getOfferStatus()
    {
        $orders_with_that_offer = MainOrder::where('offer_id',$this->id)->count();
        if($this->status == 'active' && now() < $this->end_date && $orders_with_that_offer <= $this->max_num)
        {
            $offer_status = 'active';
        }elseif(now() > $this->end_date || $orders_with_that_offer > $this->max_num){
            $offer_status = 'reactive';
        }elseif($this->status =='rejected'){
            $offer_status = 'rejected';
        }else{
            $offer_status = 'pending';
        }
        return $offer_status;
    }
}
