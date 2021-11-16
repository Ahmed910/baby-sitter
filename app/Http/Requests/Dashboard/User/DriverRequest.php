<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
        $driver = $this->driver ? $this->driver : null;
        $password = 'required|min:6|confirmed';
        $id_image = 'required|image|mimes:jpg,jpeg,png';
        if ($driver) {
            $password = 'nullable|min:6|confirmed';
            $id_image = 'nullable|image|mimes:jpg,jpeg,png';
        }
        return [
            'fullname' => 'required|string|between:2,100',
            'email' => 'nullable|email|unique:users,email,' . $driver,
            'phone' => 'required|numeric|digits_between:5,20|unique:users,phone,' . $driver,
            // 'identity_number' => 'required|numeric|digits_between:5,25|unique:users,identity_number,' . $driver,
            'identity_number_image' => $id_image,
            // 'date_of_birth' => 'nullable|required_if:user_type,driver|date|before:'.date("Y-m-d"),
            // 'date_of_birth_hijri' => 'nullable|required_if:user_type,driver|date|date_format:Y-m-d',
            'password' => $password,
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gender' => 'nullable|in:male,female',
            'is_active' => 'nullable|in:1,0',
            'is_admin_accept' => 'nullable|in:1,0',
            'is_available' => 'nullable|in:1,0',
            'is_ban' => 'nullable|in:1,0',
            'ban_reason' => 'nullable|string|between:3,10000',
            'country_id' => 'nullable|exists:countries,id,deleted_at,NULL',
            'city_id' => 'nullable|exists:cities,id,deleted_at,NULL',
            // Driver Info
            'car_id' => 'nullable|exists:cars,id,deleted_at,NULL',
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

       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }
}
