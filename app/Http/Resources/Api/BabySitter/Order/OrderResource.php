<?php

namespace App\Http\Resources\Api\BabySitter\Order;

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
        return [
            'id'=>$this->id,
            'type'=>$this->to,
            'client_order'=> new SitterOrderResource(optional($this->sitter_order)) ,
        ];
    }
}
