<?php

namespace App\Http\Requests\Client\Api\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class ChangeOrderStatus extends ApiMasterRequest
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
           // 'cancel_reason_id' => 'nullable|exists:cancel_reasons,id',
           'order_status' => 'required|in:client_cancel,client_finish,start_trip',
        ];
    }
}
