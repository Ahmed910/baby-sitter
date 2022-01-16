<?php

namespace App\Http\Requests\Api\BabySitter\Order;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResendOTPRequest extends ApiMasterRequest
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
            'order_id'=>'required|exists:main_orders,id'
        ];
    }
}
