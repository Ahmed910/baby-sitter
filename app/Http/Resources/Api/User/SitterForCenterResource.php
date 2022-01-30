<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Help\ServiceResource;
use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class SitterForCenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews = Rate::where('to_baby_sitter',$this->id)->get();
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'avatar'=>$this->image,
            'total_num_of_student'=>$this->total_num_of_student,
            'max_num_of_child_care'=>$this->max_num_of_child_care,
            'bio'=>$this->bio,

            'user_service'=>UserServiceResource::collection(optional($this->center)->user_services),
           // 'service_price'=>optional($this->center->user_services->service->service_type),
            'rate_avg'=>$this->rate_avg,
            'level_experience'=>$this->level_experience,
            'level_percentage'=>$this->level_percentage,
            'reviews'=>RateFromUserResource::collection($reviews)

        ];
    }
}
