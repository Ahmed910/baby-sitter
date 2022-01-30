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
            'id' => $this->id,
            //  'start_time'=>$this->start_time->format('g:i A'),
            //  'end_time'=>$this->end_time->format('g:i A'),
            'start' => $this->start_time->format('g:i A'),
            'end' => $this->end_time->format('g:i A'),
            'date' =>  $this->date->toFormattedDateString()
        ];
    }
}
