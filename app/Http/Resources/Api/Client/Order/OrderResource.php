<?php

namespace App\Http\Resources\Api\Client\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // $resource_data = [
        //     'id'=>$this->id,
        //     'sitter_order'=> $this->when($this->sitter_orders->count() > 0,SitterOrderResource::collection(optional($this->sitter_orders))) ,
        //     'center_order' => $this->when($this->center_orders->count() > 0,CenterOrderResource::collection(optional($this->center_orders))) ,
        // ];

       return [
            'id'=>$this->id,
            'sitter_order'=> $this->when(isset($this->sitter_order),new SitterOrderResource(optional($this->sitter_order))) ,
            'center_order' => $this->when(isset($this->center_order),new CenterOrderResource(optional($this->center_order))) ,
        ];

    }
}
