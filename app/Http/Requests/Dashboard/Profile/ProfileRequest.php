<?php

namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        return [
            'fullname' => 'required|string|between:2,200',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'required|numeric|digits_between:5,20|unique:users,phone,' . auth()->id(),
            // 'identity_number' => 'required|numeric|unique:users,identity_number,' . auth()->id(),
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gender' => 'required|in:male,female',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
        ];
    }
}
