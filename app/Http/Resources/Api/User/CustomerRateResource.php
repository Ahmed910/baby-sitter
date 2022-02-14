<?php

namespace App\Http\Resources\Api\User;

use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $rates = Rate::where('to',$this->id)->get();
        dd('ssjdjj');
        return [
            'id'=>$this->id,
            'avatar'=>$this->avatar,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'city'=>$this->CityName,
            'lat'=>optional($this->profile)->lat,
            'lng'=>optional($this->profile)->lng,
            'rates'=> RateFromUserResource::collection($this->rates)
        ];
    }
}
