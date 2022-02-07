<?php

namespace App\Http\Requests\Api\Rate;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        if(auth('api')->user()->user_type == 'childcenter' || auth('api')->user()->user_type == 'babysitter'){
            $variable = ['client'];
            $type = ['required', Rule::in([$variable])];

        }else{
            $variable = ['sitter','sitter_worker','center'];
            $type = ['required', Rule::in($variable)];
        }
        return [
            'order_id'=>'required|exists:main_orders,id',
            'type'=>$type,
            'to'=>'nullable|required_if:type,sitter|exists:users,id,user_type,babysitter',
            'to_client'=>'nullable|required_if:type,client|exists:users,id,user_type,client',
            'to_center'=>'nullable|required_if:type,center|exists:users,id,user_type,childcenter',
            'to_baby_sitter'=>'nullable|required_if:type,sitter_worker|exists:baby_sitters,id',
            'rate' => 'required|numeric|gte:0|lte:5',
            'review' => 'nullable|string|between:2,1000',
        ];
    }
}
