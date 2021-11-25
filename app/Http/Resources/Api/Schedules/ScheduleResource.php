<?php

namespace App\Http\Resources\Api\Schedules;

use App\Http\Resources\Api\Help\DayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'id'=>$this->id,
            'day' => (new DayResource($this->day)),
            // 'appointment'=> (new AppointmentResource($this->appointment))
        ];
    }
}
