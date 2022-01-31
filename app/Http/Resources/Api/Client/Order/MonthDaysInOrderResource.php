<?php

namespace App\Http\Resources\Api\Client\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class MonthDaysInOrderResource extends JsonResource
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
            'date'=>$this->date,
            'day'=>optional($this->day)->name,
            // 'start_time'=>$this->start_time->format('H:i:s'),
            // 'end_time'=>$this->end_time->format('H:i:s'),
            // 'status'=>$this->status,
        ];
    }
}
