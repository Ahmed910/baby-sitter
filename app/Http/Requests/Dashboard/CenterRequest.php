<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CenterRequest extends FormRequest
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
        $center = $this->center ? $this->center : null;



        if ($center) {
            $password = 'nullable|min:6|confirmed';
            $city = 'nullable|exists:cities,id';
            $business_license = 'nullable|image|mimes:jpeg,jpg,png';

        }else{
            $password = 'required|min:6|confirmed';
            $city = 'required|exists:cities,id';
            $business_license = 'required|image|mimes:jpeg,jpg,png';
        }
        // dd($this);
        return [
            'name' => 'required|string|between:2,100',
            'phone' => 'required|numeric|digits_between:5,20|starts_with:9665,05|unique:users,phone,' . $center,

            'password' => $password,
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'city_id'=>$city,
            'business_license_image'=>$business_license,
            'business_register'=>'required',
            'is_educational'=>'required|in:0,1',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'location' => 'required|string|between:3,250',
            'services'=>'required|array',
            'services.*.service_id'=>'nullable|required_with:services.*.price|exists:services,id',
            'services.*.price'=>'required_with:services.*.service_id',
            'features'=>'required|array',
            'features.*'=>'nullable|exists:features,id',
            'is_active' => 'nullable|in:1,0',
            'is_ban' => 'nullable|in:1,0',
            'ban_reason' => 'nullable|string|between:3,10000'
        ];
    }
}
