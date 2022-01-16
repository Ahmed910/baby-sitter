<?php

namespace App\Http\Requests\Api\Rate;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends ApiMasterRequest
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
            'order_id'=>'required|exists:main_orders,id',
            'type'=>'required|in:sitter,sitter_worker,center,client',
            'to'=>'nullable|required_if:type,sitter|exists:users,id',
            'to_client'=>'nullable|required_if:type,client|exists:users,id',
            'to_center'=>'nullable|required_if:type,center|exists:users,id',
            'to_baby_sitter'=>'nullable|required_if:type,sitter_worker|exists:baby_sitters,id',
            'rate' => 'required|numeric|gte:0|lte:5',
            'review' => 'nullable|string|between:2,1000',
        ];
    }
}
