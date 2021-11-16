<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Help\CarTypeResource;
use App\Http\Resources\Help\BrandResource;
use App\Http\Resources\Help\CarModelResource;

class CarResource extends JsonResource
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
                'car_id' => (int)$this->id,
                'car_front_image' => $this->car_front_image,
                'car_back_image' => $this->car_back_image,
                'car_licence_image' => $this->car_licence_image,
                'car_insurance_image' => $this->car_insurance_image,
                'license_serial_number' => $this->license_serial_number,
                'plate_letter_left' => $this->plate_letter_left,
                'plate_letter_right' => $this->plate_letter_right,
                'plate_letter_middle' => $this->plate_letter_middle,
                'plate_numbers_only' => $this->plate_numbers_only,
                'manufacture_year' => $this->manufacture_year,
                'plate_type' => $this->plate_type ? [
                    'id' => (int)$this->plate_type,
                    'name' => trans('dashboard.car.plate_types.'.$this->plate_type),
                ] : null,
                'car_form_image' => $this->car_form_image,
                'car_type' => $this->carType ? new CarTypeResource($this->carType) : null,
                'brand' => $this->brand ? new BrandResource($this->brand) : null,
                'car_model' => $this->carModel ? new CarModelResource($this->carModel) : null,
            ];
    }
}
