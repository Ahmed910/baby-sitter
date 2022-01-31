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
        if(isset($this->offer) && $this->offer)
        {
            $photo_validation = 'nullable|image|mimes:jpeg,jpg,png';
            $start_date = 'nullable|date_format:Y-m-d|after_or_equal:today';
            $promo_code = 'nullable';
            $discount = 'nullable|numeric|between:0,100';

        }else{
            $photo_validation = 'nullable|image|mimes:jpeg,jpg,png';
            $start_date = 'nullable|date_format:Y-m-d|after_or_equal:today';
            $promo_code = 'nullable';
            $discount = 'nullable|numeric|between:0,100';
        }
        return [
            'start_date'=>$start_date,
            'end_date'  => 'nullable|date_format:Y-m-d|after:start_date',
            'title'=>'required|string|between:2,200',
            'max_num'=>'nullable|integer',
            'promo_code'=>$promo_code,
            'discount'=>$discount,
            'photo'=>$photo_validation

        ];
    }
}
