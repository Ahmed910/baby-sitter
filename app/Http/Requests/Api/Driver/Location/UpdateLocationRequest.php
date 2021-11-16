<?php

namespace App\Http\Requests\Api\Driver\Location;

use App\Http\Requests\Api\ApiMasterRequest;

class UpdateLocationRequest extends ApiMasterRequest
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
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'location' => 'required|string|between:3,250',
        ];
    }

}
