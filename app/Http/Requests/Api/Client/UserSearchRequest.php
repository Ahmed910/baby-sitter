<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\ApiMasterRequest;

class UserSearchRequest extends ApiMasterRequest
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
           'keyword' => 'required|string|between:1,250',
        ];
    }
}
