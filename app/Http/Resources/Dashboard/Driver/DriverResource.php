<?php

namespace App\Http\Resources\Dashboard\Driver;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $driver_count = isset($this->additional['driver_count']) ? $this->additional['driver_count'] : 0;
        return [
            'data'              => SingleDriverResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($driver_count),
            "recordsFiltered"   =>  intval($driver_count),
        ];
    }
}
