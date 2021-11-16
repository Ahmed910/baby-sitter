<?php

namespace App\Http\Requests\Api\Driver\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class RejectOrderStatus extends ApiMasterRequest
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
        ];
    }
}
