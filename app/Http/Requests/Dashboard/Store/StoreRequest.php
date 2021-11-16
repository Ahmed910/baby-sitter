<?php

namespace App\Http\Requests\Dashboard\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'category_id' => 'required|exists:categories,id,deleted_at,NULL',
            'provider_id' => 'required|exists:users,id,user_type,provider,deleted_at,NULL',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'is_active' => 'required|in:0,1',
            'location' => 'required|string|between:3,250',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale.'.name'] = 'required|string|between:2,250';
            $rules[$locale.'.desc'] = 'nullable|string|between:10,100000';
        }
        return $rules;
    }
}
