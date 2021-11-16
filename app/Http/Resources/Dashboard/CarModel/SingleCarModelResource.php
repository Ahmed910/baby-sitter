<?php

namespace App\Http\Resources\Dashboard\CarModel;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleCarModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = $this;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => @$this->brand->name,
            'cars_count' => $this->cars->count(),
            'created_at' => $this->created_at->format("Y-m-d"),
            'destroy_link' => route('dashboard.car_model.destroy',$this->id),
            'edit_link' => route('dashboard.car_model.edit',$this->id),
        ];
    }
}
