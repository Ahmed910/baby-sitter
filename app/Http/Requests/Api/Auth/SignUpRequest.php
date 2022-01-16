<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Support\Str;
use App\Models\{User};

class SignUpRequest extends ApiMasterRequest
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
        $user_type='';
       // $car_liecence_image = 'nullable|required_if:user_type,driver|image|mimes:jpeg,jpg,png';
        $certificates = 'nullable|required_if:user_type,babysitter|image|mimes:jpeg,jpg,png';
        if ($this->file_type == 'file') {
            $certificates = 'nullable|required_if:user_type,babysitter|file|mimes:pdf';
        }
        if($this->user_type == 'childcenter'){
            $user_type = 'childcenter';
        }
        if($this->user_type == 'babysitter'){
            $user_type = 'babysitter';
        }

        $identity_number_validation = ($this->user_type == 'babysitter' && $this->user_type == 'client') ? 'required|numeric|digits_between:5,25|unique:users,identity_number':'nullable|numeric|digits_between:5,25|unique:users,identity_number';

        return [
            'name' => 'required|string|between:3,250',
            'password' => 'required|min:6|confirmed',
            'email'    => 'nullable|required_if:user_type,client|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone'    => 'required|numeric|digits_between:5,20|starts_with:9665,05|unique:users,phone',
            'gender'   => 'nullable|required_if:user_type,client|in:male,female',

            'image'    => 'nullable|image|mimes:jpg,jpeg,png',
            // 'country_id' => 'nullable|required_if:user_type,driver|required_with:city_id|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_type' => 'required|in:client,babysitter,childcenter',
            'is_infected' => 'nullable|in:0,1',
            'certificates' => $certificates,
            // If Driver

           // 'identity_number_image' => 'nullable|required_if:user_type,driver|image|mimes:jpg,jpeg,png',
            'identity_number' => $identity_number_validation,


            'lat' => 'nullable|required_if:user_type,childcenter|numeric',
            'lng' => 'nullable|required_if:user_type,childcenter|numeric',
            'location' => 'nullable|required_if:user_type,childcenter|string|between:3,250',
            'business_register'=>'nullable|required_if:user_type,childcenter|string',

            'is_educational'=>'nullable|required_if:user_type,childcenter|in:0,1',
            // 'price'=>'nullable|required_if:is_educational,1|numeric',
            'services'=>'nullable|array|required_if:user_type,'.$user_type,
            'services.*.service_id'=>'nullable|exists:services,id',
            
            'features'=>'nullable|array|required_if:user_type,'.$user_type,
            'features.*'=>'nullable|exists:features,id',
            'business_license_image'=>'nullable|required_if:user_type,childcenter|image|mimes:jpg,jpeg,gif,png',
            'services.*.price'=>'nullable|required_with:services.*.service_id|numeric',


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
       if(isset($data['user_type'])  && isset($data['first_name']) && isset($data['last_name']))
       {
           $data['name']=$data['first_name'].' ' .$data['last_name'];
       }

       if (isset($data['date_of_birth']) && $data['date_of_birth'] != null) {
           $data['date_of_birth'] = date('Y-m-d', strtotime($data['date_of_birth']));
       }
       if (isset($data['date_of_birth_hijri']) && $data['date_of_birth_hijri'] != null) {
           $data['date_of_birth_hijri'] = date('Y-m-d', strtotime($data['date_of_birth_hijri']));
       }
       $data['file_type'] = 'image';
       if (isset($data['certificates']) && $data['certificates'] != null) {
           if($data['certificates']->getClientMimeType() == 'application/pdf') {
               $data['file_type'] = 'file';
           }
       }


      if(isset($data['user_type'])){
        switch($data['user_type']){
            case 'childcenter':
               $data['user_type']='childcenter';
               break;
            case 'babysitter':
              $data['user_type']='babysitter';
              break;
            default:
              $data['user_type']='client';
        }
      }






       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }

}
