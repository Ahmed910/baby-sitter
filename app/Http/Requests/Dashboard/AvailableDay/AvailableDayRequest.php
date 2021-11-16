<?php

namespace App\Http\Requests\Dashboard\AvailableDay;

use Illuminate\Foundation\Http\FormRequest;

class AvailableDayRequest extends FormRequest
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

            'day' =>'required|in:sat,sun,mon,tue,wed,thu,fri',
            'district_id'=>'required|exists:districts,id'
        ];
    }
}
