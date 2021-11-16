<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
        $client = $this->client ? $this->client : null;
        $password = 'required|min:6|confirmed';

        if ($client) {
            $password = 'nullable|min:6|confirmed';
        }

        return [
            'fullname' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users,email,' . $client,
            'phone' => 'required|numeric|digits_between:5,20|starts_with:9665,05|unique:users,phone,' . $client,
            'whatsapp' => 'nullable|numeric|digits_between:5,20|unique:users,whatsapp,' . $client,
            // 'identity_number' => 'required|numeric|unique:users,identity_number,' . $client,
            'password' => $password,
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gender' => 'nullable|in:male,female',
            'is_active' => 'nullable|in:1,0',
            'is_ban' => 'nullable|in:1,0',
            'ban_reason' => 'nullable|string|between:3,10000',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
        ];
    }

    public function getValidatorInstance()
    {
       $data = $this->all();
       if (isset($data['phone']) && $data['phone']) {
           $data['phone'] = filter_mobile_number($data['phone']);
       }
       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }
}
