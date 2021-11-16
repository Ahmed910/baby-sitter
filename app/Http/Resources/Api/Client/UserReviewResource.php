<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReviewResource extends JsonResource
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
            'name' => (string) ($this->fullname ?? $this->phone),
            'image' => $this->avatar,
            'rate' => (float)$this->pivot->rate,
            'review' => $this->pivot->review,
            'created_at' => (string)optional($this->pivot->created_at)->format('Y-m-d'),
        ];
    }
}
