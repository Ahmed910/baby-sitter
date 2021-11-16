<?php

namespace App\Http\Requests\Dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $main_image = 'required|image|mimes:png,jpg,jpeg,svg,gif';
        if ($this->product) {
            $main_image = 'nullable|image|mimes:png,jpg,jpeg,svg,gif';
        }
        $rules=[
            'category_id' => 'required|exists:categories,id,deleted_at,NULL',
            'store_id' => 'required|exists:stores,id,deleted_at,NULL',

            'is_active' => 'required|in:0,1',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:png,jpg,jpeg,svg,gif',
            'main_image' => $main_image,//$main_image,
            'offer_image' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif',
            'price' => 'required|numeric|gt:0',
            'offer_price' => 'nullable|numeric|gt:0|lt:price',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules[$locale.'.name'] = 'required|string|between:2,250';
            $rules[$locale.'.desc'] = 'nullable|string|between:5,100000';
        }
        return $rules;
    }
}
