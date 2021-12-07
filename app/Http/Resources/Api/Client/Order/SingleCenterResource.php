<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
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
            'center_name'=>optional($this->center)->name,
            'sitter_name'=>optional($this->baby_sitter)->name,
            'child_center_location'=>optional(optional($this->center)->profile)->location,
            'lat'=>optional(optional($this->center)->profile)->lat,
            'lng'=>optional(optional($this->center)->profile)->lng,
            'service'=> new ServiceResource($this->service),
            'hour'=> $this->when($this->service->service_type == 'hour',new HourOrderResource($this->hours)),
            'month'=> $this->when($this->service->service_type == 'month',new MonthOrderResource($this->months)),
            'days_in_month'=> $this->when($this->service->service_type == 'month' ,isset($this->months) ? OrderDaysInMonthResource::collection($this->months->month_days):null),
            'kids'=> OrderKidsResource::collection($this->kids),
            'comment'=> $this->comment,
            'total_price'=> (float)$this->price,
            'created_at'=>$this->created_at
        ];
    }
}
