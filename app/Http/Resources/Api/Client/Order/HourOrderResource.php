<?php

namespace App\Http\Resources\Api\Client\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class HourOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($request);
        return [
            'id'=>$this->id,
             'start_time'=>$this->start_time,
             'end_time'=>$this->end_time,
             'date'=>$this->when(isset($request->sitter_order_id),$this->date)
        ];
    }
}
