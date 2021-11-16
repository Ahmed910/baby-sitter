<?php

namespace App\Http\Requests\Dashboard\Selender;

use Illuminate\Foundation\Http\FormRequest;

class SelenderRequest extends FormRequest
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
        $selender = $this->selender;
        return [
            'num_of_selenders'=>'required|integer|unique:selenders,num_of_selenders,'.$selender
        ];
    }
}
