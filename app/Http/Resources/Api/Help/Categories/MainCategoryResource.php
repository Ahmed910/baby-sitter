<?php

namespace App\Http\Resources\Api\Help\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class MainCategoryResource extends JsonResource
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
                'is_free' => (bool)$this->is_free,
                'price' => ($this->when(($this->is_free == false && $this->has_sub_category == false), $this->price)),
                'note' => (string)($this->note ?? '')
            ];

    }
}
