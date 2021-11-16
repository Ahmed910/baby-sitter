<?php

namespace App\Http\Requests\Api\Client\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class RateDriverRequest extends ApiMasterRequest
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
           'order_id' => 'required|exists:orders,id,deleted_at,NULL',
           'rate' => 'required|numeric|gte:0|lte:5',
           'review' => 'nullable|string|between:2,1000',
        ];
    }
}
