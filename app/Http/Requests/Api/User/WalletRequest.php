<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends ApiMasterRequest
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
            'amount'=>'required|numeric',
            'transaction_type'=>'required|in:withdraw,charge',
            'account_name'=>'nullable|required_if:transaction_type,withdraw',
            'bank_name'=>'nullable|required_if:transaction_type,withdraw',
            'account_number'=>'nullable|required_if:transaction_type,withdraw',
            'iban_number'=>'nullable|required_if:transaction_type,withdraw',
            'transaction_id'=>'nullable|required_if:transaction_type,charge',
        ];
    }
}
