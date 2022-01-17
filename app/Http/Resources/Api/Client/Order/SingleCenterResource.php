<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleCenterResource extends JsonResource
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
            'status'=> $this->status,
            'center_name'=>$this->when(auth('api')->user()->user_type != 'childcenter',optional($this->center)->name),
            'sitter_name'=>optional($this->baby_sitter)->name,
            'customer_data'=>$this->when(auth('api')->user()->user_type == 'childcenter',[
                'customer_id'=>optional($this->client)->id,
                'customer_name'=>optional($this->client)->name,
                'api_info'=>[
                'type'=>'GET',
                'params'=>'user_id',
                'headers'=>['token'=>'Bearer token','accept'=>'application/json'],
                'url' => route('order.customer_profile',['customer_id'=>optional($this->client)->id])
                ]
            ]),

            'child_center_location'=>$this->when(auth('api')->user()->user_type == 'client', optional(optional($this->center)->profile)->location),
            'lat'=>$this->when(auth('api')->user()->user_type == 'client',optional(optional($this->center)->profile)->lat),
            'lng'=>$this->when(auth('api')->user()->user_type == 'client',optional(optional($this->center)->profile)->lng),
            'service'=> new ServiceResource($this->service),
            'hour'=> $this->when($this->service->service_type == 'hour',new HourOrderResource($this->hours)),
            'month'=> $this->when($this->service->service_type == 'month',new MonthOrderResource($this->months)),
            'days_in_month'=> $this->when($this->service->service_type == 'month' ,isset($this->months) ? OrderDaysInMonthResource::collection($this->months->month_days):null),
            'kids'=> OrderKidsResource::collection($this->kids),
            'comment'=> $this->comment,
            
           // 'rate'=>$this->when($this->status == 'completed',new RateForSpecificOrderResource($rate)),
            'created_at'=>$this->created_at
        ];
    }
}
