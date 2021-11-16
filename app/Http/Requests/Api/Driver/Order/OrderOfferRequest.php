<?php

namespace App\Http\Requests\Api\Driver\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class OrderOfferRequest extends ApiMasterRequest
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
           'offer_price' => 'required|numeric|gte:'.((float)setting('min_offer_price') ?? 10),
           'order_id' => 'required|exists:orders,id,deleted_at,NULL,accepted_offer_id,NULL',

           'cost_reason' => 'nullable|string|between:2,100000',
        ];
    }
}
