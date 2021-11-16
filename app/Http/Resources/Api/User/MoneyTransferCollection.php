<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MoneyTransferCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'wallet' => (float) auth('api')->user()->wallet,
            'transfers' => MoneyTransferResource::collection($this->collection)
        ];
    }


}
