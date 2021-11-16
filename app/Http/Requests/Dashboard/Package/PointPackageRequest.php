<?php

namespace App\Http\Requests\Dashboard\Package;

use Illuminate\Foundation\Http\FormRequest;

class PointPackageRequest extends FormRequest
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
        $rules=[
            'transfer_type' => 'required|in:order,wallet,other',
            'amount' => 'nullable|required_if:transfer_type,order,wallet|numeric|gt:0',
            'points' => 'required|integer|gt:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'is_active' => 'nullable|in:0,1',
            'user_type' => 'required|in:client,driver,client_and_driver'
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules[$locale.'.name'] = 'required|string|between:3,250';
            $rules[$locale.'.desc'] = 'nullable|required_if:transfer_type,other|string|between:3,100000';
        }

        return $rules;

    }
}
