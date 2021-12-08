<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Client\KidResource;
use App\Http\Resources\Api\Help\ServiceResource;
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
        // dd($this->kids);
        return [
            'id'=>$this->id,
            'status'=> $this->status,
            'sitter_name'=>$this->when(auth('api')->user()->user_type == 'client',optional($this->sitter)->name),
            'customer_data'=>$this->when(auth('api')->user()->user_type == 'babysitter',[
                'customer_id'=>optional($this->client)->id,
                'customer_name'=>optional($this->client)->name,
                'type'=>'GET',
                'url' => route('sitter_order.customer_profile',['customer_id'=>optional($this->client)->id])
            ]),
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
