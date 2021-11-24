<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class KidRequest extends ApiMasterRequest
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
            'kidname'=>'required|string|between:3,200',
            'age'=>'required|numeric|gt:0',
            'health_state'=>'nullable|string',
            'image'=>'nullable|image|mimes:jpeg,jpg,png'
        ];
    }
}
