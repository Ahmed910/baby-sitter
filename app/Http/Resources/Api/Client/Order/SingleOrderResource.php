<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\User\RateForBabySitterResource;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //  dd(((isset($this->center_order) && optional($this->center_order)->status == 'completed') || (isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed')) && (auth('api')->user()->user_type != 'client'));
    //    dd(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->first());
        // dd(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_client','<>',null)->first());
        return [
            'id'=>$this->id,
            'type'=>$this->to,
            'chat_id'=>$this->when(isset($this->sitter_order),optional($this->chat)->id),
            'sitter_rate'=>$this->when(isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed',new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to','<>',null)->first())),
            'center_rate'=>$this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed',new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_center','<>',null)->first())),
            'baby_sitter_rate'=>$this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed',new RateForBabySitterResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_baby_sitter','<>',null)->first())),
            'client_rate'=>$this->when(((isset($this->center_order) && optional($this->center_order)->status == 'completed') || (isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed')) && (auth('api')->user()->user_type != 'client') ,new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_client','<>',null)->first())),
            'sitter_order'=> $this->when(isset($this->sitter_order),new SingleSitterOrderResource(optional($this->sitter_order))) ,
            'center_order' => $this->when(isset($this->center_order),new SingleCenterResource(optional($this->center_order))) ,
        ];
    }
}

