<?php

namespace App\Http\Requests\Api\Driver\Driver;

use App\Http\Requests\Api\ApiMasterRequest;

class DriverAvailableRequest extends ApiMasterRequest
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
            'is_driver_available' => "required|boolean",            
        ];
    }
}
