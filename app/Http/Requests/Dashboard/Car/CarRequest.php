<?php

namespace App\Http\Requests\Dashboard\Car;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
        $car_liecence_image = 'required|image|mimes:jpeg,jpg,png,gif';

        $car = $this->car ? $this->car->id : null;
        if ($car) {
            $car_liecence_image = 'nullable|image|mimes:jpeg,jpg,png,gif';
        }
        return [
            'brand_id' => 'required|exists:brands,id,deleted_at,NULL',
            'car_model_id' => 'required|exists:car_models,id,deleted_at,NULL',
            'car_type_id' => 'required|exists:car_types,id,deleted_at,NULL',
            // 'driver_id' => 'required|exists:users,id,deleted_at,NULL',

            'car_licence_image' => $car_liecence_image,
            'car_form_image' => $car_liecence_image,
            'car_front_image' => $car_liecence_image,
            'car_back_image' => $car_liecence_image,
            'car_insurance_image' => $car_liecence_image,

            'plate_type' => 'required|in:1,6',
            'license_serial_number' => 'required|unique:cars,license_serial_number,'.$car.',id,deleted_at,NULL',
            'plate_number' => 'required|string',
            'plate_numbers_only' => 'required|numeric',
            'plate_letters' => 'required|string',
            'plate_letter_right' => 'required|string|size:1',
            'plate_letter_middle' => 'required|string|size:1',
            'plate_letter_left' => 'required|string|size:1',

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
