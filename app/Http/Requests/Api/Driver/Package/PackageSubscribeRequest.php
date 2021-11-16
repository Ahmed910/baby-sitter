<?php

namespace App\Http\Requests\Api\Driver\Package;

use App\Http\Requests\Api\ApiMasterRequest;

class PackageSubscribeRequest extends ApiMasterRequest
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
           'package_id' => 'required|exists:packages,id,deleted_at,NULL',
           'transaction_id' => 'required|string|between:1,250',
           'transaction_data' => 'nullable',
        ];
    }
}
