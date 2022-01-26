<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
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
        $order = $this->to == 'sitter' ? $this->sitter_order : $this->center_order ;
        return [
            'id'=>$this->id,
            'type'=>$this->to,
            'total_price'=> (float)$this->price_after_offer,
            'chat_id'=>$this->when(isset($this->sitter_order),optional($this->chat)->id),
            'sitter_rate'=>$this->when(isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed',new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to','<>',null)->first())),
            'center_rate'=>$this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed',new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_center','<>',null)->first())),
            'baby_sitter_rate'=>$this->when(isset($this->center_order) && optional($this->center_order)->status == 'completed',new RateForBabySitterResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_baby_sitter','<>',null)->first())),
            'client_rate'=>$this->when(((isset($this->center_order) && optional($this->center_order)->status == 'completed') || (isset($this->sitter_order) && optional($this->sitter_order)->status == 'completed')) && (auth('api')->user()->user_type != 'client') ,new RateForSpecificOrderResource(Rate::where(['from'=>auth('api')->id(),'order_id'=>$this->id])->where('to_client','<>',null)->first())),
            //  'order_data'=> new OrderDetailsResource($this->to == 'sitter' ? $this->sitter_order:$this->center_order)
            'status'=> $order->status,
            // 'provider_name'=>$this->when(auth('api')->user()->user_type == 'client',$this->to == 'sitter' ? optional($this->sitter)->name : optional($this->center)->name),
            'provider' => $this->when(auth('api')->user()->user_type == 'client',new ProviderResource($this->to == 'sitter' ? $this->sitter : $this->center)),
            'sitter_worker_name_in_center'=>$this->when($this->to == 'center',optional($order->baby_sitter)->name),
            'customer_data'=>$this->when(auth('api')->user()->user_type == 'babysitter',[
                'customer_id'=>optional($this->client)->id,
                'customer_name'=>optional($this->client)->name,
                'api_info'=>[
                    'type'=>'GET',
                    'params'=>'user_id',
                    'headers'=>['token'=>'Bearer token','accept'=>'application/json'],
                    'url' => route('order.customer_profile',['customer_id'=>optional($this->client)->id])
                    ]
                ]),
            'qr_code'=>$this->when(auth('api')->user()->user_type == 'babysitter' && $this->to == 'sitter',$order->qrCode),
            'child_center_location'=>$this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center', optional(optional($this->center)->profile)->location),
            'lat'=>$this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center',optional(optional($this->center)->profile)->lat),
            'lng'=>$this->when(auth('api')->user()->user_type == 'client' && $this->to == 'center',optional(optional($this->center)->profile)->lng),

            'customer_location'=>$this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter', optional(optional($this->client)->profile)->location),
            'customer_lat'=>$this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter',optional(optional($this->client)->profile)->lat),
            'customer_lng'=>$this->when(auth('api')->user()->user_type != 'childcenter' && $this->to == 'sitter',optional(optional($this->client)->profile)->lng),

            'service'=> new ServiceResource($order->service),
            'service_details'=>optional($order->service)->service_type == 'hour' ? new HourOrderResource($order->hours) : new MonthOrderResource($order->months),
            'days_in_month'=> $this->when(optional($order->service)->service_type == 'month' ,isset($order->months) ? OrderDaysInMonthResource::collection($order->months->month_days):null),
            'kids'=> OrderKidsResource::collection($order->kids),
            'comment'=> $order->comment,

           // 'rate'=>$order->when($order->status == 'completed',new RateForSpecificOrderResource($rate)),
            'created_at'=>$order->created_at->toFormattedDateString()

            // 'sitter_order'=> $this->when(isset($this->sitter_order),new SingleSitterOrderResource(optional($this->sitter_order))) ,
            // 'center_order' => $this->when(isset($this->center_order),new SingleCenterResource(optional($this->center_order))) ,
        ];
    }
}

