<?php

namespace App\Http\Requests\Api\Help;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class FilterSittersRequest extends ApiMasterRequest
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
            'lowest_price' => 'nullable|numeric|gt:0',
            'high_price'  => 'nullable|numeric|gt:lowest_price',
            'high_rate'   => 'nullable|in:on'
        ];
    }
}
