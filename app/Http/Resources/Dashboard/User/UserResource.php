<?php

namespace App\Http\Resources\Dashboard\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_count = isset($this->additional['user_count']) ? $this->additional['user_count'] : 0;
        return [
            'data'              => SingleUserResource::collection($this->collection),
            "draw"              =>  intval($request->draw),
            "recordsTotal"      =>  intval($user_count),
            "recordsFiltered"   =>  intval($user_count),
        ];
    }
}
