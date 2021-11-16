<?php

namespace App\Http\Requests\Dashboard\FirstSubCategory;

use Illuminate\Foundation\Http\FormRequest;

class FirstSubCategoryRequest extends FormRequest
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
            'has_sub_category'=>'required|boolean',
            'price' => 'nullable|required_if:has_sub_category,false|numeric',
            'main_category_id'=>'required|exists:main_categories,id'
         ];
         foreach (config('translatable.locales') as $locale) {
             $rules[$locale.'.name'] = 'required|string|between:2,250';
         }


         return $rules;
    }


    public function getValidatorInstance()
    {
       $data = $this->all();
       if (isset($data['price']) && $data['price'] && $data['has_sub_category'] == true) {
           $data['price'] = null;
       }
       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }
}
