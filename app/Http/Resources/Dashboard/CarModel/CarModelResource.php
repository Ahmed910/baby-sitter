<?php

namespace App\Http\Resources\Dashboard\CarModel;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarModelResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $car_model_count = isset($this->additional['car_model_count']) ? $this->additional['car_model_count'] : 0;
        return [
            'data'              => SingleCarModelResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($car_model_count),
            "recordsFiltered"   =>  intval($car_model_count),
        ];
    }
}
