<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiMasterRequest;

class EditProfileRequest extends ApiMasterRequest
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
        $user = auth('api')->user();



        return [
           'name' => 'required|string|between:3,250',
           'email'    => 'nullable|email|unique:users,email,'.$user->id,
           'phone'    => 'required|numeric|digits_between:5,20|unique:users,phone,'.$user->id,
           'is_infected' => 'nullable|in:0,1',
           'business_register'=>'nullable|string',
           'business_license_image'=>'nullable|image|mimes:jpg,jpeg,gif,png',
           'image'    => 'nullable|image|mimes:jpg,jpeg,png',
           'identity_number' => 'nullable|numeric|digits_between:5,25|unique:users,identity_number,'.$user->id,
           'price'=>'nullable|required_if:is_educational,1|numeric',
           'country_id' => 'nullable|exists:countries,id,deleted_at,NULL',
           'city_id' => 'nullable|exists:cities,id,deleted_at,NULL',
            'lat' =>  'nullable|numeric',
            'lng' =>'nullable|numeric',
            'location' => 'nullable|string|between:3,250',
            'services'=>'nullable|array',
            'services.*.service_id'=>'nullable|exists:services,id',
            'services.*.price'=>'nullable|required_with:services.*.service_id|numeric',
            'is_educational'=>'nullable|in:0,1',
            'bio' => 'nullable|string|between:3,250',
        ];
    }

    public function getValidatorInstance()
    {
       $user = auth('api')->user();
       $data = $this->all();

       if (isset($data['phone']) && $data['phone']) {
           $data['phone'] = filter_mobile_number($data['phone']);
       }
       if (isset($data['identity_number']) && $data['identity_number']) {
           $data['identity_number'] = convertArabicNumber($data['identity_number']);
       }

       if($user->user_type == 'client'  && isset($data['first_name']) && isset($data['last_name']))
       {
           $data['name']=$data['first_name'].' ' .$data['last_name'];
       }

       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }

}
