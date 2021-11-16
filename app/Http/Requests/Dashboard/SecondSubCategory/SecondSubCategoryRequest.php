<?php

namespace App\Http\Requests\Dashboard\SecondSubCategory;

use Illuminate\Foundation\Http\FormRequest;

class SecondSubCategoryRequest extends FormRequest
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

            'price' => 'required|numeric',
            'sub_category_id'=>'required|exists:sub_categories,id'
         ];
         foreach (config('translatable.locales') as $locale) {
             $rules[$locale.'.name'] = 'required|string|between:2,250';
         }

         return $rules;
    }
}
