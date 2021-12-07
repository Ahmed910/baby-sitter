<?php

namespace App\Http\Resources\Api\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class SenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_type' => $this->user_type,
            'fullname' => $this->fullname,
            'image' => (string) $this->avatar,
        ];
    }
}
