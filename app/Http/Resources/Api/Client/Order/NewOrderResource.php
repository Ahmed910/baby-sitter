<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\Help\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NewOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $order = $this->to == 'sitter' ? $this->sitter_order : $this->center_order;
        $service_type = optional($order->service)->service_type;
        return [
            'id' => $this->id,
            'type' => $this->to,
            'status' => $this->to == 'sitter' ? $order->status : $order->status,
            'provider' => new ProviderResource($this->to == 'sitter' ? $this->sitter : $this->center),
            'service' => new ServiceResource($order->service),
            'service_details'=>$service_type == 'hour' ? new HourOrderResource($order->hours) : new MonthOrderResource($order->months),
            // 'service_type' => [
            //     'type' => $service_type,
            //     'dates_times' => $service_type == 'month' ? new MonthOrderResource($order->months) : new HourOrderResource($order->hours)
            // ],
        ];
    }
}
