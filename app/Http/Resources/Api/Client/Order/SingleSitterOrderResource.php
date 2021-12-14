<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Client\KidResource;
use App\Http\Resources\Api\Help\ServiceResource;
use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleSitterOrderResource extends JsonResource
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
            'sitter_name'=>$this->when(auth('api')->user()->user_type == 'client',optional($this->sitter)->name),
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
            //'baby_sitter_name'=>$this->when(auth('api')->user()->user_type == 'childcenter',optional($this->baby_sitter)->name),
            'qr_code'=>$this->when(auth('api')->user()->user_type == 'babysitter',$this->qrCode),
            'service'=> new ServiceResource($this->service),
            'hour'=> $this->when($this->service->service_type == 'hour',new HourOrderResource($this->hours)),
            'month'=> $this->when($this->service->service_type == 'month',new MonthOrderResource($this->months)),
            'days_in_month'=> $this->when($this->service->service_type == 'month' ,isset($this->months) ? OrderDaysInMonthResource::collection($this->months->month_days):null),
            'customer_location'=> $this->location,
            'lat'=>$this->lat,
            'lng'=> $this->lng,
            'kids'=> OrderKidsResource::collection($this->kids),
            'comment'=> $this->comment,
            'total_price'=> (float)$this->price,
            
            'created_at'=>$this->created_at
        ];
    }
}
