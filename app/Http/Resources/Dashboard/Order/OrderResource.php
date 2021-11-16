<?php

namespace App\Http\Resources\Dashboard\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order_count = isset($this->additional['order_count']) ? $this->additional['order_count'] : 0;
        return [
            'data'              => SingleOrderResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($order_count),
            "recordsFiltered"   =>  intval($order_count),
        ];
    }
}
