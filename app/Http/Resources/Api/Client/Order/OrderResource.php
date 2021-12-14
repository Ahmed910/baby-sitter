<?php

namespace App\Http\Resources\Api\Client\Order;

use App\Http\Resources\Api\User\RateForSpecificOrderResource;
use App\Models\Rate;
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
           
            'sitter_order'=> $this->when(isset($this->sitter_order),new SitterOrderResource(optional($this->sitter_order))) ,
            'center_order' => $this->when(isset($this->center_order),new CenterOrderResource(optional($this->center_order))) ,
        ];

    }
}
