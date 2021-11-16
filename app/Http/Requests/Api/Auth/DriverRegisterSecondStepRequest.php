<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Support\Str;

class DriverRegisterSecondStepRequest extends ApiMasterRequest
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
        $car_liecence_image = 'nullable|required_if:user_type,driver|image|mimes:jpeg,jpg,png';
        return [
            'car_licence_image' => $car_liecence_image,
            'car_form_image' => $car_liecence_image,
            'car_front_image' => $car_liecence_image,
            'car_back_image' => $car_liecence_image,
            // 'car_insurance_image' => $car_liecence_image,
            'user_id' => 'nullable|required_if:user_type,driver|exists:users,id,user_type,driver',
            'car_color' => 'nullable|required_if:user_type,driver|string|between:3,30',
            'brand_id' => 'nullable|required_if:user_type,driver|exists:brands,id,deleted_at,NULL',
            'car_model_id' => 'nullable|required_if:user_type,driver|exists:car_models,id,deleted_at,NULL',

            'car_type_id' => 'nullable|required_if:user_type,driver|exists:car_types,id,deleted_at,NULL',

            'plate_number' => 'nullable|required_if:user_type,driver|string|size:7',
            'manufacture_year' => ['nullable','regex:/(^\d{4}$)/'],
        ];
    }

    public function getValidatorInstance()
    {
       $data = $this->all();
       if (isset($data['phone']) && $data['phone']) {
           $data['phone'] = filter_mobile_number($data['phone']);
       }
       if (isset($data['identity_number']) && $data['identity_number']) {
           $data['identity_number'] = convertArabicNumber($data['identity_number']);
       }
       if (isset($data['date_of_birth']) && $data['date_of_birth'] != null) {
           $data['date_of_birth'] = date('Y-m-d', strtotime($data['date_of_birth']));
       }
       if (isset($data['date_of_birth_hijri']) && $data['date_of_birth_hijri'] != null) {
           $data['date_of_birth_hijri'] = date('Y-m-d', strtotime($data['date_of_birth_hijri']));
       }

       $data['health_file_type'] = 'image';
       if (isset($data['health_certificate']) && $data['health_certificate'] != null) {
           if($data['health_certificate']->getClientMimeType() == 'application/pdf') {
               $data['health_file_type'] = 'file';
           }
       }
       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }

}
