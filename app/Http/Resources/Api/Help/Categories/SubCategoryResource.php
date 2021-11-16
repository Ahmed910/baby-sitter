<?php

namespace App\Http\Resources\Api\Help\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
                'id' => $this->id,
                'name' => (string)$this->name,
                'has_sub_category' => (bool)$this->has_sub_category,
                'price' => ($this->when($this->has_sub_category != true, $this->price)),
                'main_category' => new MainCategoryResource($this->mainCategory)
            ];

    }
}
