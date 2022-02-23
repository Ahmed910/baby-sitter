<?php

namespace App\Http\Requests\Api\BabySitter\Offers;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayOfferRequest extends ApiMasterRequest
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
            'pay_type'=>'required|in:credit,wallet',
            'transaction_id'=>'nullable|required_if:pay_type,credit',
            'offer_fees'=>['required','numeric',Rule::in([setting('offer_fees')])]
        ];
    }
}
