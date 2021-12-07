<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\DayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDaysInMonthResource extends JsonResource
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
            'day'=>new DayResource($this->day),
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time
        ];
    }
}
