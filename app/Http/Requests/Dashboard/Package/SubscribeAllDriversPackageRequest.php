<?php

namespace App\Http\Requests\Dashboard\Package;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeAllDriversPackageRequest extends FormRequest
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
             'number_of_days' => 'required|integer|gt:0',
             'is_paid' => 'required|boolean',
         ];

     }

     public function getValidatorInstance()
     {
         $data = $this->all();
         if (isset($data['is_paid']) && $data['is_paid'] == 'on') {
             $data['is_paid'] = true;
         }else{
             $data['is_paid'] = false;
         }
         $this->getInputSource()->replace($data);
         return parent::getValidatorInstance();
     }
}
