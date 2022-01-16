<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransationsResource extends JsonResource
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
            'avatar'=>optional($this->transfered_user)->avatar,
            'name'=>optional($this->transfered_user)->name,
            'created_at'=>$this->created_at,
            'amount'=>$this->amount
        ];
    }
}
