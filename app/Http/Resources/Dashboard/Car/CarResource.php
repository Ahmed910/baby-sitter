<?php

namespace App\Http\Resources\Dashboard\Car;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $car_count = isset($this->additional['car_count']) ? $this->additional['car_count'] : 0;
        return [
            'data'              => SingleCarResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($car_count),
            "recordsFiltered"   =>  intval($car_count),
        ];
    }
}
