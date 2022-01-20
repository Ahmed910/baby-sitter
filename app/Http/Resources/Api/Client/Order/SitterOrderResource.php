<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SitterOrderResource extends JsonResource
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
            'status'=>$this->status,
            'sitter'=> new SitterResource($this->sitter),
            'service'=>new ServiceResource($this->service),
            'service_type'=>optional($this->service)->service_type,
            'month'=> $this->when($this->service->service_type == 'month',new MonthOrderResource($this->months)),
            'hour'=> $this->when($this->service->service_type == 'hour',new HourOrderResource($this->hours))

        ];
    }
}
