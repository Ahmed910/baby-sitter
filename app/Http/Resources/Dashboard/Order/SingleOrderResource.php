<?php

namespace App\Http\Resources\Dashboard\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = $this;
        return [
            'id' => $this->id,
            'client' => view('dashboard.order.ajax.client_link',['client' => $this->client])->render(),
            'driver' => view('dashboard.order.ajax.driver_link',['driver' => $this->driver])->render(),
            'order_type' => trans('dashboard.order.order_types.'.$this->order_type),
            'order_statuses' => view('dashboard.order.ajax.order_status',compact('order'))->render(),
            'created_at' => $this->created_at->format("Y-m-d"),
            'show_link' => route('dashboard.order.show',$this->id),
        ];
    }
}
