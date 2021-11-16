<?php

namespace App\Http\Requests\Api\Driver\Car;

use App\Http\Requests\Api\ApiMasterRequest;

class CarRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $car = auth('api')->user()->car;
        $validate = 'required';
        if ($car) {
            $validate = 'nullable';
        }
        return [
            'brand_id' => "{$validate}|exists:brands,id,deleted_at,NULL",
            'car_model_id' => "{$validate}|exists:car_models,id,deleted_at,NULL",
            'car_type_id' => "{$validate}|exists:car_types,id,deleted_at,NULL",
            // 'driver_id' => 'required|exists:users,id,deleted_at,NULL',

            'car_licence_image' => "{$validate}|image|mimes:jpeg,jpg,png,gif",
            'car_form_image' => "{$validate}|image|mimes:jpeg,jpg,png,gif",
            'car_front_image' => "{$validate}|image|mimes:jpeg,jpg,png,gif",
            'car_back_image' => "{$validate}|image|mimes:jpeg,jpg,png,gif",
            'car_insurance_image' => "{$validate}|image|mimes:jpeg,jpg,png,gif",

            'plate_type' => "{$validate}|in:1,6",
            'license_serial_number' => "{$validate}|unique:cars,license_serial_number,". @$car->id.",id,deleted_at,NULL",
            'plate_number' => "{$validate}|string",
            'plate_numbers_only' => "{$validate}|numeric|digits_between:3,4",
            'plate_letters' => "{$validate}|string",
            'plate_letter_right' => "{$validate}|string|size:1",
            'plate_letter_middle' => "{$validate}|string|size:1",
            'plate_letter_left' => "{$validate}|string|size:1",

            'manufacture_year' => ['nullable','regex:/(^\d{4}$)/'],
        ];
    }


    public function getValidatorInstance()
    {
       $data = $this->all();

       $data['plate_number'] = '';
        if ((isset($data['plate_letter_right']) && $data['plate_letter_right'] != null)
            && (isset($data['plate_letter_middle']) && $data['plate_letter_middle'] != null)
            && (isset($data['plate_letter_left']) && $data['plate_letter_left'] != null)
        ) {
            $data['plate_letters'] = $data['plate_letter_right'] . ' ' . $data['plate_letter_middle'] . ' ' . $data['plate_letter_left'];
        }
        if(isset($data['plate_numbers']) && $data['plate_numbers'] != null){
            $data['plate_numbers_only'] = $data['plate_numbers'];
        }
        if(isset($data['plate_numbers_only']) && isset($data['plate_letters'])){
            $data['plate_number'] = $data['plate_letters'] . '_' . $data['plate_numbers_only'];
        }

       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }
}
