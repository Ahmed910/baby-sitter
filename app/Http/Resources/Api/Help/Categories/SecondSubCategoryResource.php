<?php

namespace App\Http\Resources\Api\Help\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class SecondSubCategoryResource extends JsonResource
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
            'name'=>(string)$this->name,
            'price_for_one_litre'=>$this->price,
            'sub_category'=>new SubCategoryResource($this->subCategory)
        ];
    }
}
