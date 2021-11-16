<?php

namespace App\Http\Requests\Api\Driver\Driver;

use App\Http\Requests\Api\ApiMasterRequest;

class ChangeAccountStatusRequest extends ApiMasterRequest
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
            'is_trip_active' => "nullable|boolean",
            'is_city_to_city_active' => "nullable|boolean",
            'is_delivery_active' => "nullable|boolean",            
        ];
    }
}
