<?php

namespace App\Http\Resources\Dashboard\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client_count = isset($this->additional['client_count']) ? $this->additional['client_count'] : 0;
        return [
            'data'              => SingleClientResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($client_count),
            "recordsFiltered"   =>  intval($client_count),
        ];
    }
}
