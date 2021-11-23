<?php

namespace App\Http\Requests\Api\Features;

use Illuminate\Foundation\Http\FormRequest;

class EditFeaturesRequest extends FormRequest
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
            'features'=>'nullable|array',
            'features.*'=>'nullable|exists:features,id',
        ];
    }
}
