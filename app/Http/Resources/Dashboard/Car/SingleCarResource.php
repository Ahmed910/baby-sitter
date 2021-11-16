<?php

namespace App\Http\Resources\Dashboard\Car;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleCarResource extends JsonResource
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
            'image' => @$this->car_form_image,
            'brand_id' => @$this->brand->name,
            'car_model_id' => @$this->carModel->name,
            'car_type_id' => @$this->carType->name,
            'driver_name' => @$this->driver->fullname,
            'driver_link' => $this->driver_id ? route('dashboard.driver.show',$this->driver_id) : '',
            'created_at' => $this->created_at->format("Y-m-d"),
            'show_link' => route('dashboard.car.show',$this->id),
            'destroy_link' => route('dashboard.car.destroy',$this->id),
            'edit_link' => route('dashboard.car.edit',$this->id),
        ];
    }
}
