<?php

namespace App\Http\Requests\Api\BabySitter\Offers;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends ApiMasterRequest
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
            'start_date'=>'required|date_format:Y-m-d',
            'end_date'  => 'required|date_format:Y-m-d',
            'title'=>'required|string|between:2,200',
            'max_num'=>'nullable|integer',
            'promo_code'=>'required',
            'discount'=>'required|numeric|between:0,100',


        ];
    }
}
